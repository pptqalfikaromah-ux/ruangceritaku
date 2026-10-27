<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYearRequest;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Services\AcademicYearService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function __construct(
        protected AcademicYearService $service
    ) {}

    public function index(): View
    {
        $academicYears = AcademicYear::with('semesters')
            ->latest()
            ->paginate(10);

        return view('panel.tahun-ajaran.index', compact('academicYears'));
    }

    public function store(AcademicYearRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $year = AcademicYear::create([
                'name' => $request->name,
                'is_active' => false,
            ]);

            // Otomatis buat 2 semester: Ganjil dan Genap
            $year->semesters()->createMany([
                ['name' => 'Ganjil', 'is_active' => false],
                ['name' => 'Genap', 'is_active' => false],
            ]);
        });

        return redirect()->route('panel.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan beserta Semester Ganjil & Genap.');
    }

    public function update(AcademicYearRequest $request, AcademicYear $tahun_ajaran): RedirectResponse
    {
        $tahun_ajaran->update([
            'name' => $request->name,
        ]);

        return redirect()->route('panel.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $tahun_ajaran): RedirectResponse
    {
        if ($tahun_ajaran->is_active) {
            return back()->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus.');
        }

        $tahun_ajaran->delete();

        return redirect()->route('panel.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function activateSemester(Semester $semester): RedirectResponse
    {
        $this->service->activateSemester($semester);

        return redirect()->route('panel.tahun-ajaran.index')
            ->with('success', "Semester {$semester->name} ({$semester->academicYear->name}) berhasil diaktifkan.");
    }
}
