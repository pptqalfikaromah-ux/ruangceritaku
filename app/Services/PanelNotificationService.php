<?php

namespace App\Services;

use App\Models\CounselingRequest;
use App\Models\HomeroomComplaint;
use App\Models\ImsAssessment;
use App\Models\StudentComplaint;
use Illuminate\Support\Collection;

class PanelNotificationService
{
    /**
     * Hitung ringkasan notifikasi belum dibaca untuk badge header & sidebar
     */
    public function getCounts(): array
    {
        try {
            $unreadCounseling = CounselingRequest::whereNull('read_at')->count();
            $unreadComplaints = StudentComplaint::whereNull('read_at')->count();
            
            $highRiskComplaints = StudentComplaint::whereNull('read_at')
                ->where(function ($q) {
                    $q->where('safety_risk_flag', true)
                      ->orWhereIn('urgency_level', ['tinggi', 'darurat']);
                })->count();

            $unreadHomeroom = HomeroomComplaint::whereNull('read_at')->count();
            $unreadIms = ImsAssessment::where('status', 'submitted')->whereNull('read_at')->count();

            $totalUnread = $unreadCounseling + $unreadComplaints + $unreadHomeroom + $unreadIms;

            return [
                'total' => $totalUnread,
                'counseling' => $unreadCounseling,
                'complaints' => $unreadComplaints,
                'high_risk_complaints' => $highRiskComplaints,
                'has_danger' => $highRiskComplaints > 0,
                'homeroom_complaints' => $unreadHomeroom,
                'all_complaints' => ($unreadComplaints + $unreadHomeroom),
                'ims' => $unreadIms,
            ];
        } catch (\Throwable $e) {
            return [
                'total' => 0,
                'counseling' => 0,
                'complaints' => 0,
                'high_risk_complaints' => 0,
                'has_danger' => false,
                'homeroom_complaints' => 0,
                'all_complaints' => 0,
                'ims' => 0,
            ];
        }
    }

    /**
     * Ambil daftar item notifikasi terbaru untuk dropdown lonceng
     */
    public function getRecentNotifications(int $limit = 8): Collection
    {
        $items = collect();

        try {
            // 1. Aduan Murid Belum Dibaca
            $complaints = StudentComplaint::with('student')
                ->whereNull('read_at')
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($c) {
                    $isDanger = $c->safety_risk_flag || in_array($c->urgency_level, ['tinggi', 'darurat']);
                    return [
                        'id' => $c->id,
                        'type' => 'aduan_murid',
                        'title' => $isDanger ? 'PERINGATAN BAHAYA: ' . $c->category : 'Aduan Murid: ' . $c->category,
                        'sender' => $c->student?->name ?? $c->reporter_name ?? 'Murid',
                        'time' => $c->created_at,
                        'time_ago' => $c->created_at?->diffForHumans() ?? 'Baru saja',
                        'url' => route('panel.pengaduan.show', $c->id),
                        'is_danger' => $isDanger,
                        'icon' => $isDanger ? 'bi-exclamation-triangle-fill text-danger' : 'bi-megaphone-fill text-warning',
                        'badge_label' => $isDanger ? 'DARURAT / BAHAYA' : 'Aduan Baru',
                        'badge_class' => $isDanger ? 'bg-danger text-white' : 'bg-warning-subtle text-warning-emphasis',
                    ];
                });
            $items = $items->merge($complaints);

            // 2. Pengajuan Konseling Belum Dibaca
            $counselings = CounselingRequest::with('student')
                ->whereNull('read_at')
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'type' => 'konseling',
                        'title' => 'Pengajuan Konseling: ' . \Illuminate\Support\Str::limit($k->purpose, 40),
                        'sender' => $k->student?->name ?? 'Murid',
                        'time' => $k->created_at,
                        'time_ago' => $k->created_at?->diffForHumans() ?? 'Baru saja',
                        'url' => route('panel.konseling.show', $k->id),
                        'is_danger' => false,
                        'icon' => 'bi-chat-heart-fill text-primary-teal',
                        'badge_label' => 'Konseling',
                        'badge_class' => 'bg-teal-subtle text-primary-teal',
                    ];
                });
            $items = $items->merge($counselings);

            // 3. Aduan Kasus Wali Kelas Belum Dibaca
            $homerooms = HomeroomComplaint::with(['teacher', 'student'])
                ->whereNull('read_at')
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($h) {
                    return [
                        'id' => $h->id,
                        'type' => 'aduan_walas',
                        'title' => 'Laporan Walas: ' . \Illuminate\Support\Str::limit($h->title, 40),
                        'sender' => $h->teacher?->name ?? 'Wali Kelas',
                        'time' => $h->created_at,
                        'time_ago' => $h->created_at?->diffForHumans() ?? 'Baru saja',
                        'url' => route('panel.aduan-walikelas.show', $h->id),
                        'is_danger' => false,
                        'icon' => 'bi-shield-exclamation text-info',
                        'badge_label' => 'Wali Kelas',
                        'badge_class' => 'bg-info-subtle text-info-emphasis',
                    ];
                });
            $items = $items->merge($homerooms);

            // 4. Pengisian IMS Belum Dibaca
            $imsList = ImsAssessment::with('student')
                ->where('status', 'submitted')
                ->whereNull('read_at')
                ->latest('submitted_at')
                ->take($limit)
                ->get()
                ->map(function ($ims) {
                    return [
                        'id' => $ims->id,
                        'type' => 'ims',
                        'title' => 'Instrumen IMS Masuk',
                        'sender' => $ims->student?->name ?? 'Murid',
                        'time' => $ims->submitted_at ?? $ims->created_at,
                        'time_ago' => ($ims->submitted_at ?? $ims->created_at)?->diffForHumans() ?? 'Baru saja',
                        'url' => route('panel.ims.monitoring'),
                        'is_danger' => false,
                        'icon' => 'bi-clipboard-check-fill text-success',
                        'badge_label' => 'IMS 180',
                        'badge_class' => 'bg-success-subtle text-success',
                    ];
                });
            $items = $items->merge($imsList);

            // Urutkan item dari yang paling baru
            return $items->sortByDesc('time')->take($limit)->values();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Tandai semua item notifikasi sebagai telah dibaca
     */
    public function markAllAsRead(): void
    {
        $now = now();
        StudentComplaint::whereNull('read_at')->update(['read_at' => $now]);
        CounselingRequest::whereNull('read_at')->update(['read_at' => $now]);
        HomeroomComplaint::whereNull('read_at')->update(['read_at' => $now]);
        ImsAssessment::where('status', 'submitted')->whereNull('read_at')->update(['read_at' => $now]);
    }
}
