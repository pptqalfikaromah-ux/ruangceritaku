<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentComplaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentComplaintController extends Controller
{
    /**
     * Riwayat Pengaduan & Laporan Perlindungan Murid
     */
    public function index(): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $complaints = StudentComplaint::where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        return view('student.pengaduan.index', compact('complaints'));
    }

    /**
     * Form Pengaduan Aman Murid
     */
    public function create(): View
    {
        $categories = [
            'Perundungan / Bullying',
            'Kekerasan Fisik / Verbal',
            'Pelecehan / Keamanan Diri',
            'Keluarga & Pengasuhan',
            'Pemerasan / Keuangan',
            'Tekanan Belajar / Akademik',
            'Lainnya',
        ];

        return view('student.pengaduan.create', compact('categories'));
    }

    /**
     * Simpan Laporan Pengaduan Aman
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        $request->validate([
            'category' => ['required', 'string', 'max:150'],
            'urgency_level' => ['required', 'in:rendah,sedang,tinggi,darurat'],
            'incident_date' => ['nullable', 'date'],
            'incident_location' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:3000'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        $dateStr = date('Ymd');
        $randomSeq = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        $ticketNumber = "PGD-{$dateStr}-{$randomSeq}";

        $category = $request->input('category');
        $urgency = $request->input('urgency_level');

        // Flag potential safety risk automatically
        $isSafetyRisk = in_array($urgency, ['darurat', 'tinggi']) ||
            str_contains(strtolower($category), 'pelecehan') ||
            str_contains(strtolower($category), 'kekerasan') ||
            str_contains(strtolower($category), 'bullying');

        StudentComplaint::create([
            'ticket_number' => $ticketNumber,
            'student_id' => $student->id,
            'is_anonymous' => false,
            'reporter_name' => $student->name,
            'urgency_level' => $urgency,
            'category' => $category,
            'incident_date' => $request->input('incident_date'),
            'incident_location' => $request->input('incident_location'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'safety_risk_flag' => $isSafetyRisk,
        ]);

        return redirect()->route('murid.pengaduan.index')
            ->with('success', "Laporan pengaduan Anda berhasil dikirim dengan kode rahasia #{$ticketNumber}. Guru BK akan melindungi keamanan dan kerahasiaan Anda.");
    }

    /**
     * Detail Status Pengaduan Murid
     */
    public function show(StudentComplaint $pengaduan): View
    {
        $student = Auth::user()->student;
        abort_unless($student && $pengaduan->student_id === $student->id, 403);

        return view('student.pengaduan.show', compact('pengaduan'));
    }
}
