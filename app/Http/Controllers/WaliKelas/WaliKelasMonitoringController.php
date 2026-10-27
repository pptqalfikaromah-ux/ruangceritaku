<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\CounselingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WaliKelasMonitoringController extends Controller
{
    /**
     * Tampilkan monitoring sesi konseling murid binaan
     */
    public function index(Request $request): View
    {
        $teacher = Auth::user()->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;

        $studentIds = $classroom ? $classroom->students()->pluck('students.id')->toArray() : [];

        $query = CounselingRequest::with(['student', 'assignedCounselor.user', 'schedule', 'serviceType'])
            ->whereIn('student_id', $studentIds);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%");
                  });
            });
        }

        $counselings = $query->latest()->paginate(15)->withQueryString();

        return view('walikelas.monitoring.index', compact(
            'classroom',
            'counselings'
        ));
    }

    /**
     * Tampilkan detail sesi konseling murid
     */
    public function show(CounselingRequest $konseling): View
    {
        $teacher = Auth::user()->teacher;
        $classroom = $teacher?->currentAssignment?->classroom;
        $studentIds = $classroom ? $classroom->students()->pluck('students.id')->toArray() : [];

        abort_unless(in_array($konseling->student_id, $studentIds), 403, 'Akses dibatasi hanya untuk murid rombel binaan Anda.');

        $konseling->load(['student', 'assignedCounselor.user', 'schedule', 'service', 'imsAssessment']);

        return view('walikelas.monitoring.show', compact('konseling', 'classroom'));
    }
}
