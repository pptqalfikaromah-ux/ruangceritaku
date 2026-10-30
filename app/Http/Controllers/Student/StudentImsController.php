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
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (app_setting('ims_enabled', '1') !== '1') {
                return redirect()->route('murid.dashboard')
                    ->with('warning', 'Layanan Instrumen Masalah Siswa (IMS) saat ini sedang dinonaktifkan oleh pihak madrasah.');
            }
            return $next($request);
        });
    }

    /**
     * Halaman 1: Pemilihan Menu Layanan Konseling (9 Layanan)
     */
    public function index(Request $request): View|RedirectResponse
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
                ->pluck('service_type_id')
                ->countBy()
                ->toArray();
        }

        $totalSelectedCount = count($answeredStatementIds);

        $heavyStatementIds = ImsAnswer::where('ims_assessment_id', $assessment->id)
            ->where('is_selected', true)
            ->where('is_heavy', true)
            ->pluck('ims_statement_id')
            ->toArray();

        $heavyByService = [];
        if (!empty($heavyStatementIds)) {
            $heavyByService = ImsStatement::whereIn('id', $heavyStatementIds)
                ->pluck('statement_number', 'service_type_id')
                ->toArray();
        }

        $totalHeavyCount = count($heavyStatementIds);

        return view('student.ims.index', compact(
            'student',
            'assessment',
            'activeSemester',
            'activeYear',
            'serviceTypes',
            'selectedCountByService',
            'heavyByService',
            'totalSelectedCount',
            'totalHeavyCount'
        ));
    }

    /**
     * Halaman 2: Instrumen Butir Masalah per Layanan (Langsung memuat seluruh butir layanan)
     */
    public function selectField($serviceId): View|RedirectResponse
    {
        return $this->showServiceInstrument($serviceId);
    }

    /**
     * Tampilkan seluruh butir instrumen untuk satu Layanan BK
     */
    public function showServiceInstrument($serviceId): View|RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        if (!$activeSemester) {
            return view('student.ims.no-semester');
        }

        $service = ServiceType::findOrFail($serviceId);

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

        // Ambil seluruh butir pernyataan aktif dari layanan ini (20 butir)
        $statements = ImsStatement::with('guidanceField')
            ->where('service_type_id', $service->id)
            ->where('is_active', true)
            ->orderBy('statement_number', 'asc')
            ->get();

        $statementIds = $statements->pluck('id')->toArray();

        $existingAnswers = ImsAnswer::where('ims_assessment_id', $assessment->id)
            ->whereIn('ims_statement_id', $statementIds)
            ->get()
            ->keyBy('ims_statement_id');

        // Cari butir terberat yang sudah ditandai di layanan ini (jika ada)
        $heavyStatementId = null;
        foreach ($existingAnswers as $ans) {
            if ($ans->is_heavy && $ans->is_selected) {
                $heavyStatementId = $ans->ims_statement_id;
                break;
            }
        }

        // Ambil urutan layanan untuk navigasi Sebelumnya / Selanjutnya
        $allServices = ServiceType::layanan()->orderBy('id')->get();
        $currentIndex = $allServices->search(fn($item) => $item->id == $service->id);
        $prevService = ($currentIndex !== false && $currentIndex > 0) ? $allServices->get($currentIndex - 1) : null;
        $nextService = ($currentIndex !== false && $currentIndex < $allServices->count() - 1) ? $allServices->get($currentIndex + 1) : null;

        return view('student.ims.instrument', compact(
            'student',
            'assessment',
            'service',
            'statements',
            'existingAnswers',
            'heavyStatementId',
            'allServices',
            'currentIndex',
            'prevService',
            'nextService'
        ));
    }

    /**
     * Fallback showInstrument untuk backward compatibility
     */
    public function showInstrument($serviceId, $fieldId = null): View|RedirectResponse
    {
        return $this->showServiceInstrument($serviceId);
    }

    /**
     * Simpan Pilihan Butir Masalah & 1 Masalah Terberat (Opsional) untuk Layanan ini
     */
    public function saveServiceInstrument(Request $request, $serviceId): RedirectResponse
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
        $heavyStatementId = $request->input('heavy_statement_id'); // single statement ID (opsional)
        $scopeStatementIds = $request->input('scope_statement_ids', []); // seluruh statement IDs layanan ini

        DB::transaction(function () use ($assessment, $selectedStatements, $heavyStatementId, $scopeStatementIds) {
            foreach ($scopeStatementIds as $stmtId) {
                $isSelected = in_array($stmtId, $selectedStatements);
                // Hanya bisa menjadi masalah terberat jika butir tersebut juga dipilih!
                $isHeavy = $isSelected && ($heavyStatementId == $stmtId);

                if ($isSelected) {
                    ImsAnswer::updateOrCreate(
                        [
                            'ims_assessment_id' => $assessment->id,
                            'ims_statement_id' => $stmtId,
                        ],
                        [
                            'is_selected' => true,
                            'is_heavy' => $isHeavy,
                        ]
                    );
                } else {
                    ImsAnswer::where('ims_assessment_id', $assessment->id)
                        ->where('ims_statement_id', $stmtId)
                        ->delete();
                }
            }
        });

        $action = $request->input('action', 'save');

        if ($action === 'to_index') {
            return redirect()->route('murid.ims.index')
                ->with('success', 'Pilihan butir masalah berhasil disimpan.');
        }

        if ($action === 'to_next' && $request->filled('next_service_id')) {
            return redirect()->route('murid.ims.service', $request->input('next_service_id'))
                ->with('success', 'Pilihan butir masalah berhasil disimpan. Lanjut ke layanan berikutnya.');
        }

        return redirect()->route('murid.ims.service', $serviceId)
            ->with('success', 'Pilihan butir masalah dan masalah terberat berhasil disimpan.');
    }

    /**
     * Fallback saveInstrument
     */
    public function saveInstrument(Request $request, $serviceId, $fieldId = null): RedirectResponse
    {
        return $this->saveServiceInstrument($request, $serviceId);
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
            'consult_to' => $request->input('consult_to', $assessment->consult_to),
            'submitted_at' => now(),
        ]);

        return redirect()->route('murid.ims.result')
            ->with('success', 'Instrumen Masalah Siswa (IMS) Anda berhasil diselesaikan dan dikirimkan. Berikut adalah Lembar Hasil Akhir Asesmen Anda.');
    }

    /**
     * Halaman Hasil Akhir Asesmen IMS Murid (Standar Format AUM)
     */
    public function result(): View|RedirectResponse
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

        if (!$assessment) {
            return redirect()->route('murid.ims.index')->with('info', 'Anda belum memulai pengisian asesmen IMS.');
        }

        $assessment->load([
            'student.classrooms',
            'academicYear',
            'semester',
            'answers.statement.serviceType',
            'answers.statement.guidanceField',
        ]);

        $services = ServiceType::layanan()->orderBy('id')->get();
        $servicesData = [];
        $totalSelectedAll = 0;
        $totalStatementsAll = 0;
        $heavyStatementsAll = [];

        foreach ($services as $service) {
            $serviceStatementsCount = ImsStatement::where('service_type_id', $service->id)
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->count();

            // Selected answers in this service
            $selectedAnswers = $assessment->answers->filter(function ($a) use ($service) {
                return $a->statement?->service_type_id === $service->id && $a->is_selected;
            })->sortBy(function ($a) {
                return $a->statement?->statement_number;
            });

            // Heavy answers in this service
            $heavyAnswers = $selectedAnswers->filter(function ($a) {
                return (bool) $a->is_heavy;
            });

            $selectedNumbers = $selectedAnswers->pluck('statement.statement_number')->map(function ($num) {
                return sprintf('%03d', $num);
            })->values()->all();

            $heavyNumbers = $heavyAnswers->pluck('statement.statement_number')->map(function ($num) {
                return sprintf('%03d', $num);
            })->values()->all();

            $count = count($selectedNumbers);
            $percentage = $serviceStatementsCount > 0 ? round(($count / $serviceStatementsCount) * 100, 2) : 0;

            $servicesData[] = [
                'service' => $service,
                'field' => $service, // alias for blade
                'total_statements' => $serviceStatementsCount,
                'selected_numbers' => $selectedNumbers,
                'selected_answers' => $selectedAnswers,
                'count' => $count,
                'percentage' => $percentage,
                'heavy_numbers' => $heavyNumbers,
                'heavy_answers' => $heavyAnswers,
            ];

            $totalSelectedAll += $count;
            $totalStatementsAll += $serviceStatementsCount;
            foreach ($heavyAnswers as $ha) {
                $heavyStatementsAll[] = $ha;
            }
        }

        $fieldsData = $servicesData;
        $overallPercentage = $totalStatementsAll > 0 ? round(($totalSelectedAll / $totalStatementsAll) * 100, 2) : 0;

        return view('student.ims.result', compact(
            'student',
            'assessment',
            'servicesData',
            'fieldsData',
            'totalSelectedAll',
            'totalStatementsAll',
            'overallPercentage',
            'heavyStatementsAll'
        ));
    }

    /**
     * Cetak Lembar Hasil AUM / IMS Murid
     */
    public function printResult(): View|RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $activeSemester = active_semester();
        $assessment = ImsAssessment::where('student_id', $student->id)
            ->where('semester_id', $activeSemester?->id)
            ->first();

        if (!$assessment) {
            return redirect()->route('murid.ims.index');
        }

        $controller = app(\App\Http\Controllers\Panel\ImsController::class);
        return $controller->printIndividual($assessment);
    }
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
