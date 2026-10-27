<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CounselingRequest;
use App\Models\CounselingRequestHistory;
use App\Models\Counselor;
use App\Models\CounselorAssignment;
use App\Models\GuidanceField;
use App\Models\ImsAnswer;
use App\Models\ImsAssessment;
use App\Models\ImsCustomStatement;
use App\Models\ImsStatement;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentImsController extends Controller
{
    /**
     * Halaman 1: Pemilihan Menu Layanan Konseling (9 Layanan)
     */
    public function index(Request $request): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil murid tidak ditemukan.');

        $activeSemester = active_semester();
        $activeYear = active_academic_year();

        if (!$activeSemester) {
            return view('student.ims.no-semester');
        }

        $assessment = ImsAssessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id,
            ],
            [
                'academic_year_id' => $activeSemester->academic_year_id,
                'status' => 'draft',
            ]
        );

        $serviceTypes = ServiceType::layanan()->orderBy('id')->get();

        // Hitung berapa butir yang sudah terisi per layanan
        $answeredStatementIds = ImsAnswer::where('ims_assessment_id', $assessment->id)
            ->where('is_selected', true)
            ->pluck('ims_statement_id')
            ->toArray();

        $selectedCountByService = [];
        if (!empty($answeredStatementIds)) {
            $selectedCountByService = ImsStatement::whereIn('id', $answeredStatementIds)
                ->select('service_type_id', DB::raw('count(*) as total'))
                ->groupBy('service_type_id')
                ->pluck('total', 'service_type_id')
                ->toArray();
        }

        return view('student.ims.index', compact(
            'student',
            'assessment',
            'activeSemester',
            'activeYear',
            'serviceTypes',
            'selectedCountByService'
        ));
    }

    /**
     * Halaman 2: Pemilihan Menu Bidang Masalah (Pribadi, Sosial, Belajar, Karir)
     */
    public function selectField($serviceId): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        if (!$activeSemester) {
            return view('student.ims.no-semester');
        }

        $service = ServiceType::findOrFail($serviceId);
        $guidanceFields = GuidanceField::whereIn('code', ['BID-01', 'BID-02', 'BID-03', 'BID-04'])->orderBy('id')->get();
        if ($guidanceFields->isEmpty()) {
            $guidanceFields = GuidanceField::orderBy('id')->get();
        }

        $assessment = ImsAssessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id,
            ],
            [
                'academic_year_id' => $activeSemester->academic_year_id,
                'status' => 'draft',
            ]
        );

        // Ambil statement yang sudah dicentang untuk layanan ini
        $serviceStatementIds = ImsStatement::where('service_type_id', $service->id)->pluck('id');
        $answeredFieldIds = ImsAnswer::where('ims_assessment_id', $assessment->id)
            ->whereIn('ims_statement_id', $serviceStatementIds)
            ->where('is_selected', true)
            ->join('ims_statements', 'ims_answers.ims_statement_id', '=', 'ims_statements.id')
            ->pluck('ims_statements.guidance_field_id')
            ->toArray();

        $selectedCountByField = array_count_values($answeredFieldIds);

        return view('student.ims.select-field', compact(
            'student',
            'assessment',
            'service',
            'guidanceFields',
            'selectedCountByField'
        ));
    }

    /**
     * Halaman 3: Instrumen Butir Masalah yang Dipilih + Kolom Catatan Lebar
     */
    public function showInstrument($serviceId, $fieldId): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        if (!$activeSemester) {
            return view('student.ims.no-semester');
        }

        $service = ServiceType::findOrFail($serviceId);
        $field = GuidanceField::findOrFail($fieldId);

        $assessment = ImsAssessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id,
            ],
            [
                'academic_year_id' => $activeSemester->academic_year_id,
                'status' => 'draft',
            ]
        );

        // Ambil tepat 5 butir pernyataan yang sesuai
        $statements = ImsStatement::where('service_type_id', $service->id)
            ->where('guidance_field_id', $field->id)
            ->where('is_active', true)
            ->orderBy('statement_number')
            ->get();

        $statementIds = $statements->pluck('id')->toArray();

        $existingAnswers = ImsAnswer::where('ims_assessment_id', $assessment->id)
            ->whereIn('ims_statement_id', $statementIds)
            ->get()
            ->keyBy('ims_statement_id');

        // Ambil butir kustom buatan siswa sendiri untuk rumpun dan bidang ini
        $customStatements = ImsCustomStatement::where('ims_assessment_id', $assessment->id)
            ->where('service_type_id', $service->id)
            ->where('guidance_field_id', $field->id)
            ->get();

        // Cari catatan lebar umum yang tersimpan sebelumnya untuk bagian ini
        $wideNote = null;
        foreach ($existingAnswers as $ans) {
            if (!empty($ans->student_note)) {
                $wideNote = $ans->student_note;
                break;
            }
        }

        return view('student.ims.instrument', compact(
            'student',
            'assessment',
            'service',
            'field',
            'statements',
            'existingAnswers',
            'customStatements',
            'wideNote'
        ));
    }

    /**
     * Simpan Jawaban Butir, Catatan per Butir & Butir Masalah Kustom
     */
    public function saveInstrument(Request $request, $serviceId, $fieldId): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        abort_unless($activeSemester, 400, 'Semester aktif belum ditentukan.');

        $assessment = ImsAssessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id,
            ],
            [
                'academic_year_id' => $activeSemester->academic_year_id,
                'status' => 'draft',
            ]
        );

        if ($assessment->isSubmitted()) {
            return back()->with('error', 'Instrumen IMS ini sudah dikirimkan dan tidak dapat diubah.');
        }

        $selectedStatements = $request->input('selected', []); // array of statement IDs
        $statementNotes = $request->input('notes', []); // array of [stmt_id => note]
        $wideNote = $request->input('wide_note');
        $scopeStatementIds = $request->input('scope_statement_ids', []);

        DB::transaction(function () use ($assessment, $serviceId, $fieldId, $selectedStatements, $statementNotes, $wideNote, $scopeStatementIds, $request) {
            // 1. Simpan Butir Standar & Catatan per Butir
            foreach ($scopeStatementIds as $stmtId) {
                $isSelected = in_array($stmtId, $selectedStatements);
                $itemNote = $statementNotes[$stmtId] ?? null;
                if (empty($itemNote) && !empty($wideNote) && $isSelected) {
                    $itemNote = $wideNote;
                }

                if ($isSelected || !empty($itemNote)) {
                    ImsAnswer::updateOrCreate(
                        [
                            'ims_assessment_id' => $assessment->id,
                            'ims_statement_id' => $stmtId,
                        ],
                        [
                            'is_selected' => $isSelected,
                            'student_note' => $itemNote,
                        ]
                    );
                } else {
                    ImsAnswer::where('ims_assessment_id', $assessment->id)
                        ->where('ims_statement_id', $stmtId)
                        ->delete();
                }
            }

            // 2. Simpan Butir Kustom Baru (Jika Murid Menambahkan)
            if ($request->filled('new_custom_text')) {
                ImsCustomStatement::create([
                    'ims_assessment_id' => $assessment->id,
                    'service_type_id' => $serviceId,
                    'guidance_field_id' => $fieldId,
                    'statement_text' => trim($request->input('new_custom_text')),
                    'student_note' => trim($request->input('new_custom_note') ?? ''),
                    'is_selected' => true,
                ]);
            }

            // 3. Hapus Butir Kustom jika ada aksi hapus
            if ($request->filled('delete_custom_id')) {
                ImsCustomStatement::where('ims_assessment_id', $assessment->id)
                    ->where('id', $request->input('delete_custom_id'))
                    ->delete();
            }

            // 4. Update Butir Kustom yang Sudah Ada
            $customNotes = $request->input('custom_notes', []);
            $selectedCustom = $request->input('selected_custom', []);
            foreach ($customNotes as $cId => $note) {
                ImsCustomStatement::where('ims_assessment_id', $assessment->id)
                    ->where('id', $cId)
                    ->update([
                        'student_note' => $note,
                        'is_selected' => in_array($cId, $selectedCustom),
                    ]);
            }
        });

        // Aksi Cepat: Lanjut ke Konseling IMS
        if ($request->input('action') === 'counseling') {
            return redirect()->route('murid.ims.counseling.create')
                ->with('success', 'Pilihan masalah IMS berhasil disimpan. Lanjutkan pengajuan permohonan konseling.');
        }

        return redirect()->route('murid.ims.instrument', [$serviceId, $fieldId])
            ->with('success', 'Pilihan butir masalah dan catatan Anda berhasil disimpan.');
    }

    /**
     * Kompatibilitas Simpan Draft Global
     */
    public function saveDraft(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        abort_unless($activeSemester, 400, 'Semester aktif belum ditentukan.');

        $assessment = ImsAssessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id,
            ],
            [
                'academic_year_id' => $activeSemester->academic_year_id,
                'status' => 'draft',
            ]
        );

        if ($assessment->isSubmitted()) {
            return back()->with('error', 'Instrumen IMS ini sudah dikirimkan dan tidak dapat diubah.');
        }

        $selectedStatements = $request->input('selected', []);
        $notes = $request->input('notes', []);

        DB::transaction(function () use ($assessment, $selectedStatements, $notes, $request) {
            if ($request->has('final_notes')) {
                $assessment->update(['final_notes' => $request->input('final_notes')]);
            }

            $submittedScopeIds = $request->input('scope_statement_ids', []);
            if (empty($submittedScopeIds)) {
                $submittedScopeIds = ImsStatement::where('is_active', true)->pluck('id')->toArray();
            }

            foreach ($submittedScopeIds as $stmtId) {
                $isSelected = in_array($stmtId, $selectedStatements);
                $note = $notes[$stmtId] ?? null;

                if ($isSelected || !empty($note)) {
                    ImsAnswer::updateOrCreate(
                        [
                            'ims_assessment_id' => $assessment->id,
                            'ims_statement_id' => $stmtId,
                        ],
                        [
                            'is_selected' => $isSelected,
                            'student_note' => $note,
                        ]
                    );
                } else {
                    ImsAnswer::where('ims_assessment_id', $assessment->id)
                        ->where('ims_statement_id', $stmtId)
                        ->delete();
                }
            }
        });

        if ($request->input('action') === 'counseling') {
            return redirect()->route('murid.ims.counseling.create')
                ->with('success', 'Pilihan masalah IMS berhasil disimpan. Silakan tentukan jadwal sesi konseling yang kamu harapkan.');
        }

        return back()->with('success', 'Pilihan masalah dan catatan Anda berhasil disimpan.');
    }

    /**
     * Kirimkan Lembar IMS Akhir (Final Submit)
     */
    public function submit(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        abort_unless($activeSemester, 400, 'Semester aktif belum ditentukan.');

        $assessment = ImsAssessment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id,
            ],
            [
                'academic_year_id' => $activeSemester->academic_year_id,
                'status' => 'draft',
            ]
        );

        if ($assessment->isSubmitted()) {
            return back()->with('error', 'Instrumen IMS ini sudah dikirimkan sebelumnya.');
        }

        $assessment->update([
            'status' => 'submitted',
            'final_notes' => $request->input('final_notes', $assessment->final_notes),
            'submitted_at' => now(),
        ]);

        return redirect()->route('murid.ims.index')
            ->with('success', 'Instrumen Masalah Siswa (IMS) Anda berhasil dikirimkan ke Guru BK / Konselor.');
    }

    /**
     * Halaman Khusus: Permohonan Konseling Berdasarkan Hasil Temuan IMS
     */
    public function scheduleCounselingForm(): View|RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil murid tidak ditemukan.');

        $activeSemester = active_semester();
        if (!$activeSemester) {
            return view('student.ims.no-semester');
        }

        $assessment = ImsAssessment::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        $selectedAnswers = collect();
        $customStatements = collect();
        if ($assessment) {
            $selectedAnswers = ImsAnswer::with(['statement.guidanceField', 'statement.serviceType'])
                ->where('ims_assessment_id', $assessment->id)
                ->where('is_selected', true)
                ->get();

            $customStatements = ImsCustomStatement::with(['guidanceField', 'serviceType'])
                ->where('ims_assessment_id', $assessment->id)
                ->where('is_selected', true)
                ->get();
        }

        // Daftar Guru BK Aktif
        $counselors = Counselor::where('status', 'aktif')->with('user')->get();

        // Guru BK pembina rombel murid
        $studentClassroom = $student->classrooms->first();
        $assignedCounselor = null;
        if ($studentClassroom) {
            $assignment = CounselorAssignment::where('classroom_id', $studentClassroom->id)
                ->where('semester_id', $activeSemester->id)
                ->with('counselor.user')
                ->first();
            $assignedCounselor = $assignment?->counselor;
        }

        // Cek apakah murid sudah punya tiket pending dari IMS
        $existingPending = CounselingRequest::where('student_id', $student->id)
            ->where('source', 'ims')
            ->whereIn('status', ['pending', 'needs_information'])
            ->first();

        return view('student.ims.schedule-counseling', compact(
            'student',
            'assessment',
            'selectedAnswers',
            'customStatements',
            'counselors',
            'assignedCounselor',
            'existingPending'
        ));
    }

    /**
     * Simpan Pengajuan Konseling Tindak Lanjut IMS (Waktu ditentukan Guru BK / Admin)
     */
    public function storeCounselingFromIms(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'Profil murid tidak ditemukan.');

        $activeSemester = active_semester();
        abort_unless($activeSemester, 400, 'Semester aktif belum ditentukan.');

        $request->validate([
            'counselor_id' => ['nullable', 'exists:counselors,id'],
            'counseling_method' => ['nullable', 'in:tatap_muka,online_meet'],
            'student_expectation' => ['nullable', 'string', 'max:1000'],
        ]);

        $assessment = ImsAssessment::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        $selectedAnswers = collect();
        $customStatements = collect();
        if ($assessment) {
            $selectedAnswers = ImsAnswer::with(['statement.guidanceField', 'statement.serviceType'])
                ->where('ims_assessment_id', $assessment->id)
                ->where('is_selected', true)
                ->get();

            $customStatements = ImsCustomStatement::with(['guidanceField', 'serviceType'])
                ->where('ims_assessment_id', $assessment->id)
                ->where('is_selected', true)
                ->get();
        }

        // Hindari duplikasi pengajuan konseling IMS yang masih pending
        $existingPending = CounselingRequest::where('student_id', $student->id)
            ->where('source', 'ims')
            ->whereIn('status', ['pending', 'needs_information'])
            ->first();

        if ($existingPending) {
            return redirect()->route('murid.konseling.show', $existingPending->id)
                ->with('info', "Anda sudah memiliki pengajuan konseling IMS yang sedang ditinjau dengan nomor tiket #{$existingPending->ticket_number}.");
        }

        // Tentukan bidang & layanan utama dari butir pertama yang dipilih
        $firstAnswer = $selectedAnswers->first();
        $firstCustom = $customStatements->first();
        $guidanceFieldId = $firstAnswer?->statement?->guidance_field_id ?? $firstCustom?->guidance_field_id;
        $serviceTypeId = $firstAnswer?->statement?->service_type_id ?? $firstCustom?->service_type_id;

        // Susun teks rangkuman masalah yang dipilih dari IMS
        $descLines = [
            "=== MASALAH YANG DIPILIH SISWA DARI INSTRUMEN IMS ===",
        ];

        if ($selectedAnswers->isEmpty() && $customStatements->isEmpty()) {
            $descLines[] = "(Murid belum menandai butir spesifik, namun mengajukan permohonan konseling dari instrumen IMS)";
        } else {
            foreach ($selectedAnswers as $ans) {
                $stmt = $ans->statement;
                $line = "• [Butir #{$stmt->statement_number}] {$stmt->statement_text} (Bidang {$stmt->guidanceField?->name})";
                if (!empty($ans->student_note)) {
                    $line .= "\n  Catatan Murid: {$ans->student_note}";
                }
                $descLines[] = $line;
            }

            if ($customStatements->isNotEmpty()) {
                $descLines[] = "\n--- MASALAH TAMBAHAN DIBUAT SENDIRI OLEH SISWA ---";
                foreach ($customStatements as $cStmt) {
                    $line = "• [Butir Tambahan] {$cStmt->statement_text} (Bidang {$cStmt->guidanceField?->name} - {$cStmt->serviceType?->name})";
                    if (!empty($cStmt->student_note)) {
                        $line .= "\n  Catatan Murid: {$cStmt->student_note}";
                    }
                    $descLines[] = $line;
                }
            }
        }

        if (!empty($assessment?->final_notes)) {
            $descLines[] = "\nCatatan Tambahan di Lembar IMS:\n" . $assessment->final_notes;
        }

        $formattedSchedule = "Menunggu Penentuan Jadwal oleh Guru BK/Admin";

        $counselorId = $request->input('counselor_id');
        if (!$counselorId && $student->classrooms->first()) {
            $assignment = CounselorAssignment::where('classroom_id', $student->classrooms->first()->id)
                ->where('semester_id', $activeSemester->id)
                ->first();
            $counselorId = $assignment?->counselor_id;
        }

        $dateStr = date('Ymd');
        $randomSeq = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        $ticketNumber = "AGD-{$dateStr}-{$randomSeq}";

        DB::transaction(function () use ($student, $assessment, $ticketNumber, $guidanceFieldId, $serviceTypeId, $descLines, $formattedSchedule, $counselorId, $request) {
            $counseling = CounselingRequest::create([
                'ticket_number' => $ticketNumber,
                'source' => 'ims',
                'student_id' => $student->id,
                'ims_assessment_id' => $assessment?->id,
                'purpose' => 'Konseling Tindak Lanjut Masalah IMS',
                'counseling_method' => $request->input('counseling_method', 'tatap_muka'),
                'guidance_field_id' => $guidanceFieldId,
                'service_type_id' => $serviceTypeId,
                'is_service_unknown' => false,
                'description' => implode("\n", $descLines),
                'preferred_schedule' => $formattedSchedule,
                'additional_notes' => $request->input('student_expectation'),
                'assigned_counselor_id' => $counselorId,
                'status' => 'pending',
            ]);

            CounselingRequestHistory::create([
                'counseling_request_id' => $counseling->id,
                'user_id' => Auth::id(),
                'status_from' => 'draft',
                'status_to' => 'pending',
                'notes' => 'Permohonan konseling tindak lanjut IMS diajukan oleh murid. Waktu sesi akan ditentukan dan disetujui oleh Guru BK/Admin.',
            ]);
        });

        return redirect()->route('murid.konseling.index')
            ->with('success', "Pengajuan konseling IMS berhasil diajukan dengan nomor tiket #{$ticketNumber}. Guru BK akan menetapkan dan mengonfirmasi waktu serta ruangan sesi.");
    }
}
