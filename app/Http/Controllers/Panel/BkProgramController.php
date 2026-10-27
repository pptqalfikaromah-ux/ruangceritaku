<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\BkProgram;
use App\Models\BkRpl;
use App\Models\Counselor;
use App\Models\GuidanceField;
use App\Models\Semester;
use App\Models\ServiceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BkProgramController extends Controller
{
    /**
     * Program Tahunan (Prota) & Program Semester (Promes) BK
     */
    public function index(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $academicYearId = $request->input('academic_year_id', $activeYear?->id);
        $semesterId = $request->input('semester_id', $activeSemester?->id);
        $fieldId = $request->input('guidance_field_id');
        $serviceId = $request->input('service_type_id');

        $query = BkProgram::with(['serviceType', 'guidanceField', 'academicYear', 'semester', 'rpls'])
            ->when($academicYearId, function ($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->when($semesterId, function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            })
            ->when($fieldId, function ($q) use ($fieldId) {
                $q->where('guidance_field_id', $fieldId);
            })
            ->when($serviceId, function ($q) use ($serviceId) {
                $q->where('service_type_id', $serviceId);
            });

        $programs = $query->orderBy('month_planned')->get();

        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $semesters = Semester::orderBy('id', 'desc')->get();
        $serviceTypes = ServiceType::layanan()->get();
        $guidanceFields = GuidanceField::all();
        $counselors = Counselor::all();

        $totalPrograms = $programs->count();
        $totalRpls = BkRpl::count();

        return view('panel.program-bk.index', compact(
            'programs',
            'academicYears',
            'semesters',
            'serviceTypes',
            'guidanceFields',
            'counselors',
            'academicYearId',
            'semesterId',
            'fieldId',
            'serviceId',
            'totalPrograms',
            'totalRpls'
        ));
    }

    /**
     * Simpan Butir Program BK
     */
    public function storeProgram(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'exists:semesters,id'],
            'service_type_id' => ['required', 'exists:service_types,id'],
            'guidance_field_id' => ['required', 'exists:guidance_fields,id'],
            'name' => ['required', 'string', 'max:255'],
            'target_level' => ['required', 'string', 'max:50'],
            'month_planned' => ['nullable', 'string', 'max:50'],
            'goals' => ['nullable', 'string'],
            'evaluation_method' => ['nullable', 'string', 'max:255'],
        ]);

        BkProgram::create($request->all());

        return back()->with('success', 'Rencana Program BK berhasil ditambahkan.');
    }

    /**
     * Update Butir Program BK
     */
    public function updateProgram(Request $request, BkProgram $program): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'service_type_id' => ['required', 'exists:service_types,id'],
            'guidance_field_id' => ['required', 'exists:guidance_fields,id'],
            'target_level' => ['required', 'string', 'max:50'],
            'month_planned' => ['nullable', 'string', 'max:50'],
            'goals' => ['nullable', 'string'],
            'evaluation_method' => ['nullable', 'string', 'max:255'],
        ]);

        $program->update($request->all());

        return back()->with('success', 'Rencana Program BK berhasil diperbarui.');
    }

    /**
     * Hapus Butir Program BK
     */
    public function destroyProgram(BkProgram $program): RedirectResponse
    {
        $program->delete();
        return back()->with('success', 'Butir rencana program berhasil dihapus.');
    }

    /**
     * Form Tambah RPL
     */
    public function createRpl(Request $request): View
    {
        $programId = $request->input('program_id');
        $selectedProgram = $programId ? BkProgram::find($programId) : null;

        $serviceTypes = ServiceType::layanan()->get();
        $guidanceFields = GuidanceField::all();
        $counselors = Counselor::all();
        $programs = BkProgram::orderBy('name')->get();

        return view('panel.program-bk.rpl-form', compact(
            'selectedProgram',
            'programs',
            'serviceTypes',
            'guidanceFields',
            'counselors'
        ));
    }

    /**
     * Simpan Dokumen RPL
     */
    public function storeRpl(Request $request): RedirectResponse
    {
        $request->validate([
            'topic' => ['required', 'string', 'max:255'],
            'service_type_id' => ['required', 'exists:service_types,id'],
            'guidance_field_id' => ['required', 'exists:guidance_fields,id'],
            'counselor_id' => ['required', 'exists:counselors,id'],
            'target_class' => ['required', 'string', 'max:100'],
            'allocation_time' => ['required', 'string', 'max:100'],
            'general_goals' => ['required', 'string'],
            'specific_goals' => ['nullable', 'string'],
            'materials' => ['nullable', 'string'],
            'methods' => ['nullable', 'string'],
            'media' => ['nullable', 'string'],
            'steps' => ['nullable', 'string'],
            'evaluation' => ['nullable', 'string'],
        ]);

        $rpl = BkRpl::create($request->all());

        return redirect()->route('panel.program-bk.rpl.show', $rpl->id)
            ->with('success', 'Rencana Pelaksanaan Layanan (RPL) berhasil disusun.');
    }

    /**
     * Tampilan Dokumen Cetak RPL Standar BK
     */
    public function showRpl(BkRpl $rpl): View
    {
        $rpl->load(['program', 'counselor.user', 'serviceType', 'guidanceField']);
        return view('panel.program-bk.rpl-show', compact('rpl'));
    }

    /**
     * Hapus RPL
     */
    public function destroyRpl(BkRpl $rpl): RedirectResponse
    {
        $rpl->delete();
        return redirect()->route('panel.program-bk.index')->with('success', 'Dokumen RPL berhasil dihapus.');
    }
}
