<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\CounselingRequest;
use App\Models\GroupCounseling;
use App\Models\StudentComplaint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StudentNotificationService
{
    /**
     * Ambil timestamp terakhir murid menandai notifikasi dibaca
     */
    protected function getLastReadTimestamp(?int $userId): ?Carbon
    {
        if (!$userId) return null;
        $val = Cache::get('student_notif_read_at_' . $userId);
        return $val ? Carbon::parse($val) : null;
    }

    /**
     * Hitung ringkasan notifikasi murid untuk badge lonceng
     */
    public function getCounts(): array
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return ['total' => 0, 'counseling' => 0, 'groups' => 0, 'complaints' => 0, 'announcements' => 0];
            }

            $items = $this->getRecentNotifications(50);
            $unreadCount = $items->where('is_unread', true)->count();

            return [
                'total' => $unreadCount,
                'counseling' => $items->where('is_unread', true)->where('type', 'konseling')->count(),
                'groups' => $items->where('is_unread', true)->where('type', 'layanan_kelompok')->count(),
                'complaints' => $items->where('is_unread', true)->where('type', 'pengaduan')->count(),
                'announcements' => $items->where('is_unread', true)->where('type', 'pengumuman')->count(),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'counseling' => 0, 'groups' => 0, 'complaints' => 0, 'announcements' => 0];
        }
    }

    /**
     * Ambil daftar notifikasi terbaru untuk dropdown lonceng murid
     */
    public function getRecentNotifications(int $limit = 8): Collection
    {
        $items = collect();

        try {
            $user = Auth::user();
            if (!$user) return $items;

            $student = $user->student;
            $lastReadAt = $this->getLastReadTimestamp($user->id);

            // 1. Notifikasi BKP & KKP (Sesi Kelompok Terjadwal / Disetujui Guru BK)
            if ($student) {
                $groups = GroupCounseling::with(['counselor.user'])
                    ->whereHas('members', function ($q) use ($student) {
                        $q->where('students.id', $student->id);
                    })
                    ->latest('updated_at')
                    ->take($limit)
                    ->get()
                    ->map(function ($grp) use ($lastReadAt) {
                        $isBkp = $grp->service_type === 'bimbingan_kelompok';
                        $label = $isBkp ? 'BKP' : 'KKP';
                        $time = $grp->updated_at ?? $grp->created_at;
                        $isUnread = $lastReadAt ? $time->gt($lastReadAt) : true;

                        return [
                            'id' => 'grp_' . $grp->id,
                            'type' => 'layanan_kelompok',
                            'title' => "Jadwal {$label} Ditetapkan Guru BK",
                            'subtitle' => Str::limit($grp->topic, 38) . ' (' . ($grp->scheduled_date ? $grp->scheduled_date->translatedFormat('d M') : '-') . ', ' . ($grp->start_time ?? '-') . ' WIB)',
                            'time' => $time,
                            'time_ago' => $time?->diffForHumans() ?? 'Baru saja',
                            'url' => route('murid.layanan-kelompok.show', $grp->id),
                            'is_unread' => $isUnread,
                            'icon' => $isBkp ? 'bi-people-fill text-mint' : 'bi-heart-pulse-fill text-purple',
                            'badge_label' => "Jadwal {$label}",
                            'badge_class' => $isBkp ? 'bg-mint text-white' : 'bg-purple text-white',
                        ];
                    });
                $items = $items->merge($groups);

                // 2. Notifikasi Konseling Pribadi / Individual
                $counselings = CounselingRequest::with(['assignedCounselor.user'])
                    ->where('student_id', $student->id)
                    ->whereIn('status', ['dijadwalkan', 'proses', 'selesai', 'batal', 'ditolak'])
                    ->latest('updated_at')
                    ->take($limit)
                    ->get()
                    ->map(function ($k) use ($lastReadAt) {
                        $time = $k->updated_at ?? $k->created_at;
                        $isUnread = $lastReadAt ? $time->gt($lastReadAt) : true;

                        $statusLabel = match ($k->status) {
                            'dijadwalkan' => 'Jadwal Ditetapkan',
                            'proses' => 'Sedang Berlangsung',
                            'selesai' => 'Selesai',
                            'batal' => 'Dibatalkan',
                            'ditolak' => 'Ditolak',
                            default => ucfirst($k->status),
                        };

                        $badgeClass = match ($k->status) {
                            'dijadwalkan' => 'bg-mint text-white',
                            'selesai' => 'bg-success text-white',
                            'batal', 'ditolak' => 'bg-danger text-white',
                            default => 'bg-primary text-white',
                        };

                        return [
                            'id' => 'cns_' . $k->id,
                            'type' => 'konseling',
                            'title' => "Konseling: {$statusLabel}",
                            'subtitle' => Str::limit($k->purpose, 45) . ($k->preferred_schedule ? ' (' . $k->preferred_schedule . ')' : ''),
                            'time' => $time,
                            'time_ago' => $time?->diffForHumans() ?? 'Baru saja',
                            'url' => route('murid.konseling.show', $k->id),
                            'is_unread' => $isUnread,
                            'icon' => 'bi-calendar-check-fill text-mint',
                            'badge_label' => "Konseling {$statusLabel}",
                            'badge_class' => $badgeClass,
                        ];
                    });
                $items = $items->merge($counselings);

                // 3. Notifikasi Pengaduan & Perlindungan
                $complaints = StudentComplaint::where('student_id', $student->id)
                    ->whereIn('status', ['sedang_ditangani', 'selesai'])
                    ->latest('updated_at')
                    ->take($limit)
                    ->get()
                    ->map(function ($c) use ($lastReadAt) {
                        $time = $c->updated_at ?? $c->created_at;
                        $isUnread = $lastReadAt ? $time->gt($lastReadAt) : true;
                        $statusLabel = $c->status === 'selesai' ? 'Selesai Ditangani' : 'Sedang Ditindaklanjuti';

                        return [
                            'id' => 'cmp_' . $c->id,
                            'type' => 'pengaduan',
                            'title' => "Aduan {$statusLabel}",
                            'subtitle' => 'Kategori: ' . Str::limit($c->category, 30) . ' telah diproses Guru BK.',
                            'time' => $time,
                            'time_ago' => $time?->diffForHumans() ?? 'Baru saja',
                            'url' => route('murid.pengaduan.show', $c->id),
                            'is_unread' => $isUnread,
                            'icon' => 'bi-shield-check text-success',
                            'badge_label' => $statusLabel,
                            'badge_class' => $c->status === 'selesai' ? 'bg-success text-white' : 'bg-warning-subtle text-warning-emphasis',
                        ];
                    });
                $items = $items->merge($complaints);
            }

            // 4. Pengumuman Sekolah Baru
            $announcements = Announcement::active()
                ->forRole('murid')
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($a) use ($lastReadAt) {
                    $time = $a->created_at;
                    $isUnread = $lastReadAt ? $time->gt($lastReadAt) : true;

                    return [
                        'id' => 'ann_' . $a->id,
                        'type' => 'pengumuman',
                        'title' => 'Pengumuman: ' . Str::limit($a->title, 35),
                        'subtitle' => Str::limit(strip_tags($a->content), 45),
                        'time' => $time,
                        'time_ago' => $time?->diffForHumans() ?? 'Baru saja',
                        'url' => route('murid.dashboard'),
                        'is_unread' => $isUnread,
                        'icon' => 'bi-megaphone-fill text-warning',
                        'badge_label' => 'Pengumuman',
                        'badge_class' => 'bg-warning text-dark',
                    ];
                });
            $items = $items->merge($announcements);

            return $items->sortByDesc('time')->take($limit)->values();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Tandai semua notifikasi murid sebagai telah dibaca
     */
    public function markAllAsRead(): void
    {
        $userId = Auth::id();
        if ($userId) {
            Cache::forever('student_notif_read_at_' . $userId, now()->toDateTimeString());
        }
    }
}

