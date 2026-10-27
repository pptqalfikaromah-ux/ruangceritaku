<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\BkProgram;
use App\Models\Classroom;
use App\Models\CounselingRequest;
use App\Models\CounselingService;
use App\Models\GuidanceField;
use App\Models\ImsAssessment;
use App\Models\Semester;
use App\Models\ServiceType;
use App\Models\StudentComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Halaman Utama Rekapitulasi & Laporan BK
     */
    public function index(Request $request): View
    {
        $type = $request->input('type', 'konseling'); // konseling, ims, program, pengaduan
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $academicYearId = $request->input('academic_year_id', $activeYear?->id);
        $semesterId = $request->input('semester_id', $activeSemester?->id);
        $classroomId = $request->input('classroom_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $semesters = Semester::orderBy('id', 'desc')->get();

        // Data based on type
        $reportData = $this->fetchReportData($type, $academicYearId, $semesterId, $classroomId, $startDate, $endDate);

        return view('panel.laporan.index', compact(
            'type',
            'reportData',
            'classrooms',
            'academicYears',
            'semesters',
            'academicYearId',
            'semesterId',
            'classroomId',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Tampilan Cetak Resmi Bersih Berkop Sekolah
     */
    public function print(Request $request): View
    {
        $type = $request->input('type', 'konseling');
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $academicYearId = $request->input('academic_year_id', $activeYear?->id);
        $semesterId = $request->input('semester_id', $activeSemester?->id);
        $classroomId = $request->input('classroom_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $selectedClassroom = $classroomId ? Classroom::find($classroomId) : null;
        $selectedYear = $academicYearId ? AcademicYear::find($academicYearId) : null;
        $selectedSemester = $semesterId ? Semester::find($semesterId) : null;

        $reportData = $this->fetchReportData($type, $academicYearId, $semesterId, $classroomId, $startDate, $endDate);

        return view('panel.laporan.print', compact(
            'type',
            'reportData',
            'selectedClassroom',
            'selectedYear',
            'selectedSemester',
            'startDate',
            'endDate'
        ));
    }

    private function fetchReportData(string $type, $academicYearId, $semesterId, $classroomId, $startDate, $endDate)
    {
        switch ($type) {
            case 'konseling':
                $query = CounselingRequest::with(['student.classrooms', 'serviceType', 'guidanceField', 'assignedCounselor', 'schedule']);
                if ($classroomId) {
                    $query->whereHas('student.classrooms', function ($q) use ($classroomId) {
                        $q->where('classrooms.id', $classroomId);
                    });
                }
                if ($startDate) $query->whereDate('created_at', '>=', $startDate);
                if ($endDate) $query->whereDate('created_at', '<=', $endDate);
                return $query->latest()->get();

            case 'ims':
                $assessmentQuery = ImsAssessment::where('status', 'submitted');
                if ($semesterId) $assessmentQuery->where('semester_id', $semesterId);
                if ($academicYearId) $assessmentQuery->where('academic_year_id', $academicYearId);
                if ($classroomId) {
                    $assessmentQuery->whereHas('student.classrooms', function ($q) use ($classroomId) {
                        $q->where('classrooms.id', $classroomId);
                    });
                }
                $assessmentIds = $assessmentQuery->pluck('id');
                $respondentCount = $assessmentIds->count();

                $topProblems = [];
                if ($respondentCount > 0) {
                    $topProblems = DB::table('ims_answers')
                        ->join('ims_statements', 'ims_answers.ims_statement_id', '=', 'ims_statements.id')
                        ->join('guidance_fields', 'ims_statements.guidance_field_id', '=', 'guidance_fields.id')
                        ->join('service_types', 'ims_statements.service_type_id', '=', 'service_types.id')
                        ->whereIn('ims_answers.ims_assessment_id', $assessmentIds)
                        ->where('ims_answers.is_selected', true)
                        ->select(
                            'ims_statements.statement_number',
                            'ims_statements.statement_text',
                            'guidance_fields.name as field_name',
                            'service_types.name as service_name',
                            DB::raw('count(ims_answers.id) as total_selected')
                        )
                        ->groupBy('ims_statements.statement_number', 'ims_statements.statement_text', 'guidance_fields.name', 'service_types.name')
                        ->orderByDesc('total_selected')
                        ->limit(20)
                        ->get();
                }

                return [
                    'respondents' => $respondentCount,
                    'topProblems' => $topProblems,
                ];

            case 'program':
                return BkProgram::with(['serviceType', 'guidanceField', 'academicYear', 'semester', 'rpls'])
                    ->when($academicYearId, function ($q) use ($academicYearId) {
                        $q->where('academic_year_id', $academicYearId);
                    })
                    ->when($semesterId, function ($q) use ($semesterId) {
                        $q->where('semester_id', $semesterId);
                    })
                    ->orderBy('month_planned')
                    ->get();

            case 'pengaduan':
                $query = StudentComplaint::with(['student.classrooms', 'handledBy']);
                if ($startDate) $query->whereDate('created_at', '>=', $startDate);
                if ($endDate) $query->whereDate('created_at', '<=', $endDate);
                return $query->latest()->get();

            default:
                return [];
        }
    }
}
