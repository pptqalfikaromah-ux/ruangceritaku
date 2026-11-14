<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\HomeroomComplaint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class WaliKelasNotificationService
{
    /**
     * Ambil timestamp terakhir wali kelas menandai notifikasi dibaca
     */
    protected function getLastReadTimestamp(?int $userId): ?Carbon
    {
        if (!$userId) return null;
        $val = Cache::get('walikelas_notif_read_at_' . $userId);
        return $val ? Carbon::parse($val) : null;
    }

    /**
     * Hitung ringkasan notifikasi wali kelas untuk badge lonceng & sidebar
     */
    public function getCounts(): array
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return ['total' => 0, 'complaints' => 0, 'announcements' => 0];
            }

            $items = $this->getRecentNotifications(50);
            $unreadCount = $items->where('is_unread', true)->count();

            return [
                'total' => $unreadCount,
                'complaints' => $items->where('is_unread', true)->where('type', 'aduan_walas')->count(),
                'announcements' => $items->where('is_unread', true)->where('type', 'pengumuman')->count(),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'complaints' => 0, 'announcements' => 0];
        }
    }

    /**
     * Ambil daftar notifikasi terbaru untuk dropdown lonceng wali kelas
     */
    public function getRecentNotifications(int $limit = 8): Collection
    {
        $items = collect();

        try {
            $user = Auth::user();
            if (!$user) return $items;

            $teacher = $user->teacher;
            $lastReadAt = $this->getLastReadTimestamp($user->id);

            // 1. Notifikasi Tanggapan / Update Status Kasus dari Guru BK
            if ($teacher) {
                $complaints = HomeroomComplaint::with(['student', 'handledByCounselor.user'])
                    ->where('teacher_id', $teacher->id)
                    ->whereIn('status', ['sedang_ditangani', 'selesai'])
                    ->latest('updated_at')
                    ->take($limit)
                    ->get()
                    ->map(function ($c) use ($lastReadAt) {
                        $time = $c->updated_at ?? $c->created_at;
                        $isUnread = $lastReadAt ? $time->gt($lastReadAt) : true;
                        $statusLabel = $c->status === 'selesai' ? 'Selesai Ditangani BK' : 'Sedang Ditangani BK';

                        return [
                            'id' => 'wlc_' . $c->id,
                            'type' => 'aduan_walas',
                            'title' => "Kasus: {$statusLabel}",
                            'subtitle' => Str::limit($c->student?->name ?? 'Siswa', 25) . ' - ' . Str::limit($c->title, 35),
                            'time' => $time,
                            'time_ago' => $time?->diffForHumans() ?? 'Baru saja',
                            'url' => route('walikelas.aduan.show', $c->id),
                            'is_unread' => $isUnread,
                            'icon' => $c->status === 'selesai' ? 'bi-check-circle-fill text-success' : 'bi-hourglass-split text-warning',
                            'badge_label' => $statusLabel,
                            'badge_class' => $c->status === 'selesai' ? 'bg-success text-white' : 'bg-warning text-dark',
                        ];
                    });
                $items = $items->merge($complaints);
            }

            // 2. Pengumuman Sekolah Baru untuk Wali Kelas
            $announcements = Announcement::active()
                ->forRole('wali_kelas')
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($a) use ($lastReadAt) {
                    $time = $a->created_at;
                    $isUnread = $lastReadAt ? $time->gt($lastReadAt) : true;

                    return [
                        'id' => 'wla_' . $a->id,
                        'type' => 'pengumuman',
                        'title' => 'Pengumuman: ' . Str::limit($a->title, 35),
                        'subtitle' => Str::limit(strip_tags($a->content), 45),
                        'time' => $time,
                        'time_ago' => $time?->diffForHumans() ?? 'Baru saja',
                        'url' => route('walikelas.dashboard'),
                        'is_unread' => $isUnread,
                        'icon' => 'bi-megaphone-fill text-info',
                        'badge_label' => 'Pengumuman',
                        'badge_class' => 'bg-info text-white',
                    ];
                });
            $items = $items->merge($announcements);

            return $items->sortByDesc('time')->take($limit)->values();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Tandai semua notifikasi wali kelas sebagai telah dibaca
     */
    public function markAllAsRead(): void
    {
        $userId = Auth::id();
        if ($userId) {
            Cache::forever('walikelas_notif_read_at_' . $userId, now()->toDateTimeString());
        }
    }
}

