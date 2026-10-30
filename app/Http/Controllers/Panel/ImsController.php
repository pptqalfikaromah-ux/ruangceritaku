<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\GuidanceField;
use App\Models\ImsAnswer;
use App\Models\ImsAssessment;
use App\Models\ImsStatement;
use App\Models\Semester;
use App\Models\ServiceType;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ImsController extends Controller
{
    /**
     * Master Data 180 Butir Instrumen Masalah Siswa (IMS)
     */
    public function master(Request $request): View
    {
        $query = ImsStatement::with(['serviceType', 'guidanceField']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('statement_text', 'like', "%{$q}%")
                    ->orWhere('statement_number', $q);
            });
        }

        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->input('service_type_id'));
        }

        if ($request->filled('guidance_field_id')) {
            $query->where('guidance_field_id', $request->input('guidance_field_id'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        $statements = $query->orderBy('statement_number', 'asc')->paginate(30)->withQueryString();
        $serviceTypes = ServiceType::layanan()->get();
        $guidanceFields = GuidanceField::all();

        $totalStatements = ImsStatement::count();
        $activeStatements = ImsStatement::where('is_active', true)->count();
        $isImsGloballyEnabled = app_setting('ims_enabled', '1') === '1';

        return view('panel.ims.master', compact(
            'statements',
            'serviceTypes',
            'guidanceFields',
            'totalStatements',
            'activeStatements',
            'isImsGloballyEnabled'
        ));
    }

    /**
     * Mengaktifkan atau Menonaktifkan Fitur IMS secara Global untuk Murid
     */
    public function toggleStatus(Request $request): RedirectResponse
    {
        $currentStatus = app_setting('ims_enabled', '1');
        $newStatus = ($currentStatus === '1' || $currentStatus === true || $currentStatus === 1) ? '0' : '1';

        Setting::set(
            'ims_enabled',
            $newStatus,
            'features',
            'Status aktif fitur Instrumen Masalah Siswa (IMS) untuk akun murid'
        );

        $message = $newStatus === '1'
            ? 'Fitur Instrumen Masalah Siswa (IMS) BERHASIL DIAKTIFKAN. Menu dan instrumen IMS kini muncul di portal murid.'
            : 'Fitur Instrumen Masalah Siswa (IMS) BERHASIL DINONAKTIFKAN. Seluruh menu dan instrumen IMS disembunyikan dari portal murid.';

        return back()->with('success', $message);
    }

    /**
     * Update statement text or active status (Admin only)
     */
    public function updateStatement(Request $request, ImsStatement $statement): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'statement_text' => ['required', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $statement->update([
            'statement_text' => $request->input('statement_text'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Butir nomor #{$statement->statement_number} berhasil diperbarui.");
    }

    /**
     * Toggle statement active status
     */
    public function toggleStatement(ImsStatement $statement): RedirectResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $statement->update([
            'is_active' => !$statement->is_active,
        ]);

        $status = $statement->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Butir #{$statement->statement_number} berhasil {$status}.");
    }

    /**
     * Monitoring Pengisian Instrumen oleh Murid
     */
    public function monitoring(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $academicYearId = $request->input('academic_year_id', $activeYear?->id);
        $semesterId = $request->input('semester_id', $activeSemester?->id);
        $classroomId = $request->input('classroom_id');
        $statusFilter = $request->input('status'); // submitted, draft, belum
        $search = $request->input('q');

        // Filter classrooms
        $classrooms = Classroom::when($academicYearId, function ($q) use ($academicYearId) {
            $q->where('academic_year_id', $academicYearId);
        })->orderBy('name')->get();

        // Get student query
        $studentQuery = Student::with([
            'classrooms',
            'imsAssessments' => function ($q) use ($semesterId) {
                if ($semesterId) {
                    $q->where('semester_id', $semesterId);
                }
            }
        ]);

        if ($classroomId) {
            $studentQuery->whereHas('classrooms', function ($q) use ($classroomId) {
                $q->where('classrooms.id', $classroomId);
            });
        }

        if ($search) {
            $studentQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $allStudents = $studentQuery->get();

        // Stats calculation
        $totalStudents = $allStudents->count();
        $submittedCount = 0;
        $draftCount = 0;
        $unfilledCount = 0;

        foreach ($allStudents as $student) {
            $assessment = $student->imsAssessments->first();
            if (!$assessment) {
                $student->ims_status = 'belum';
                $unfilledCount++;
            } elseif ($assessment->status === 'submitted') {
                $student->ims_status = 'submitted';
                $student->current_assessment = $assessment;
                $submittedCount++;
            } else {
                $student->ims_status = 'draft';
                $student->current_assessment = $assessment;
                $draftCount++;
            }
        }

        // Filter by status if requested
        $filteredCollection = $allStudents;
        if ($statusFilter && in_array($statusFilter, ['submitted', 'draft', 'belum'])) {
            $filteredCollection = $allStudents->filter(function ($s) use ($statusFilter) {
                return $s->ims_status === $statusFilter;
            });
        }

        // Manual pagination on collection
        $perPage = 20;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $paginatedStudents = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredCollection->forPage($currentPage, $perPage),
            $filteredCollection->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $semesters = Semester::orderBy('id', 'desc')->get();

        $isImsGloballyEnabled = app_setting('ims_enabled', '1') === '1';

        return view('panel.ims.monitoring', compact(
            'paginatedStudents',
            'classrooms',
            'academicYears',
            'semesters',
            'academicYearId',
            'semesterId',
            'classroomId',
            'statusFilter',
            'search',
            'totalStudents',
            'submittedCount',
            'draftCount',
            'unfilledCount',
            'isImsGloballyEnabled'
        ));
    }

    /**
     * Detail Lembar Jawaban & Catatan Murid
     */
    public function showAssessment(ImsAssessment $assessment): View
    {
        if (!$assessment->read_at && $assessment->status === 'submitted') {
            $assessment->update(['read_at' => now()]);
        }

        $assessment->load([
            'student.classrooms',
            'academicYear',
            'semester',
            'answers.statement.serviceType',
            'answers.statement.guidanceField',
        ]);

        // Group answers by Service Type (8 Layanan BK)
        $services = ServiceType::layanan()->orderBy('id')->get();
        $answersByService = [];

        foreach ($services as $service) {
            $answersByService[$service->name] = $assessment->answers->filter(function ($a) use ($service) {
                return $a->statement?->service_type_id === $service->id;
            })->sortBy(function ($a) {
                return $a->statement?->statement_number;
            });
        }

        // Juga siapkan answersByField untuk fallback
        $answersByField = [];
        $guidanceFields = GuidanceField::all();

        foreach ($guidanceFields as $field) {
            $answersByField[$field->name] = $assessment->answers->filter(function ($a) use ($field) {
                return $a->statement?->guidance_field_id === $field->id;
            })->sortBy(function ($a) {
                return $a->statement?->statement_number;
            });
        }

        $totalSelected = $assessment->answers->where('is_selected', true)->count();
        $totalAnswers = $assessment->answers->count();

        // Butir masalah yang paling mengganggu (N.M.Y.B)
        $heavyAnswers = $assessment->answers
            ->where('is_selected', true)
            ->where('is_heavy', true)
            ->sortBy(function ($a) {
                return $a->statement?->statement_number;
            });

        return view('panel.ims.show', compact(
            'assessment',
            'answersByService',
            'answersByField',
            'totalSelected',
            'totalAnswers',
            'heavyAnswers'
        ));
    }

    /**
     * Cetak Laporan Format Resmi AUM (Alat Ungkap Masalah) Individual Siswa (Format 3)
     */
    public function printIndividual(ImsAssessment $assessment): View
    {
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
                'field' => $service, // alias for blade compatibility
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

        // Current counselor or fallback
        $counselor = auth()->user()?->counselor ?? auth()->user();

        return view('panel.ims.print-individual', compact(
            'assessment',
            'servicesData',
            'fieldsData',
            'totalSelectedAll',
            'totalStatementsAll',
            'overallPercentage',
            'heavyStatementsAll',
            'counselor'
        ));
    }

    /**
     * Rekapitulasi Multidimensi Instrumen Masalah Siswa (Analytics)
     */
    public function rekap(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $academicYearId = $request->input('academic_year_id', $activeYear?->id);
        $semesterId = $request->input('semester_id', $activeSemester?->id);
        $classroomId = $request->input('classroom_id');

        $classrooms = Classroom::when($academicYearId, function ($q) use ($academicYearId) {
            $q->where('academic_year_id', $academicYearId);
        })->orderBy('name')->get();

        // Base query for submitted assessments
        $assessmentQuery = ImsAssessment::where('status', 'submitted');
        if ($semesterId) {
            $assessmentQuery->where('semester_id', $semesterId);
        }
        if ($academicYearId) {
            $assessmentQuery->where('academic_year_id', $academicYearId);
        }
        if ($classroomId) {
            $assessmentQuery->whereHas('student', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            });
        }

        $assessmentIds = $assessmentQuery->pluck('id');
        $respondentCount = $assessmentIds->count();

        // 1. Ranking 15 Masalah Terbanyak (Top Problems)
        $topProblems = [];
        if ($respondentCount > 0) {
            $topProblems = DB::table('ims_answers')
                ->join('ims_statements', 'ims_answers.ims_statement_id', '=', 'ims_statements.id')
                ->join('guidance_fields', 'ims_statements.guidance_field_id', '=', 'guidance_fields.id')
                ->join('service_types', 'ims_statements.service_type_id', '=', 'service_types.id')
                ->whereIn('ims_answers.ims_assessment_id', $assessmentIds)
                ->where('ims_answers.is_selected', true)
                ->select(
                    'ims_statements.id',
                    'ims_statements.statement_number',
                    'ims_statements.statement_text',
                    'guidance_fields.name as field_name',
                    'service_types.name as service_name',
                    DB::raw('count(ims_answers.id) as total_selected')
                )
                ->groupBy(
                    'ims_statements.id',
                    'ims_statements.statement_number',
                    'ims_statements.statement_text',
                    'guidance_fields.name',
                    'service_types.name'
                )
                ->orderByDesc('total_selected')
                ->limit(15)
                ->get();
        }

        // 2. Rekapitulasi per Bidang Bimbingan (Pribadi, Sosial, Belajar, Karir)
        $fieldBreakdown = DB::table('guidance_fields')
            ->select('guidance_fields.id', 'guidance_fields.name')
            ->get()
            ->map(function ($field) use ($assessmentIds, $respondentCount) {
                if ($respondentCount === 0) {
                    return [
                        'name' => $field->name,
                        'total_selected' => 0,
                        'percentage' => 0,
                    ];
                }
                $count = DB::table('ims_answers')
                    ->join('ims_statements', 'ims_answers.ims_statement_id', '=', 'ims_statements.id')
                    ->whereIn('ims_answers.ims_assessment_id', $assessmentIds)
                    ->where('ims_statements.guidance_field_id', $field->id)
                    ->where('ims_answers.is_selected', true)
                    ->count();

                // 45 statements per field
                $possibleAnswers = $respondentCount * 45;
                $pct = $possibleAnswers > 0 ? round(($count / $possibleAnswers) * 100, 1) : 0;

                return [
                    'name' => $field->name,
                    'total_selected' => $count,
                    'percentage' => $pct,
                ];
            });

        // 3. Rekapitulasi per 9 Layanan BK
        $serviceBreakdown = ServiceType::layanan()->get()->map(function ($service) use ($assessmentIds, $respondentCount) {
            if ($respondentCount === 0) {
                return [
                    'code' => $service->code,
                    'name' => $service->name,
                    'total_selected' => 0,
                    'percentage' => 0,
                ];
            }
            $count = DB::table('ims_answers')
                ->join('ims_statements', 'ims_answers.ims_statement_id', '=', 'ims_statements.id')
                ->whereIn('ims_answers.ims_assessment_id', $assessmentIds)
                ->where('ims_statements.service_type_id', $service->id)
                ->where('ims_answers.is_selected', true)
                ->count();

            // 20 statements per service
            $possibleAnswers = $respondentCount * 20;
            $pct = $possibleAnswers > 0 ? round(($count / $possibleAnswers) * 100, 1) : 0;

            return [
                'code' => $service->code,
                'name' => $service->name,
                'total_selected' => $count,
                'percentage' => $pct,
            ];
        });

        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $semesters = Semester::orderBy('id', 'desc')->get();

        return view('panel.ims.rekap', compact(
            'classrooms',
            'academicYears',
            'semesters',
            'academicYearId',
            'semesterId',
            'classroomId',
            'respondentCount',
            'topProblems',
            'fieldBreakdown',
            'serviceBreakdown'
        ));
    }
}
