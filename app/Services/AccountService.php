<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AccountService
{
    /**
     * Generate akun murid massal (username = NISN, password awal acak/default)
     */
    public function generateStudentAccounts(?int $classroomId = null, string $defaultPassword = 'password123'): array
    {
        $query = Student::whereNull('user_id');

        if ($classroomId) {
            $query->whereHas('classrooms', function ($q) use ($classroomId) {
                $q->where('classrooms.id', $classroomId);
            });
        }

        $students = $query->get();
        $generatedCount = 0;
        $failedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($students as $student) {
                // Cek apakah username (NISN) sudah dipakai di tabel users
                $existingUser = User::where('username', $student->nisn)->first();

                if ($existingUser) {
                    // Hubungkan student ke user jika belum
                    $student->update(['user_id' => $existingUser->id]);
                    continue;
                }

                $user = User::create([
                    'name' => $student->name,
                    'username' => $student->nisn,
                    'email' => null,
                    'password' => Hash::make($defaultPassword),
                    'role' => 'murid',
                    'is_active' => true,
                    'must_change_password' => true, // Wajib ganti password saat login pertama
                ]);

                $student->update(['user_id' => $user->id]);
                $generatedCount++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'total_processed' => $students->count(),
            'generated_count' => $generatedCount,
        ];
    }

    /**
     * Export CSV kredensial akun murid
     */
    public function exportStudentCredentials(?int $classroomId = null, string $defaultPassword = 'password123'): StreamedResponse
    {
        $filename = 'kredensial_akun_murid_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($classroomId, $defaultPassword) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID Murid',
                'Nama Murid',
                'Rombel',
                'Username (NISN)',
                'Password Awal',
                'Status Akun',
                'Wajib Ganti Password'
            ]);

            $query = Student::with(['user', 'classrooms'])->whereNotNull('user_id');

            if ($classroomId) {
                $query->whereHas('classrooms', function ($q) use ($classroomId) {
                    $q->where('classrooms.id', $classroomId);
                });
            }

            $query->chunk(200, function ($students) use ($handle, $defaultPassword) {
                foreach ($students as $student) {
                    $user = $student->user;
                    $cr = $student->current_classroom;
                    fputcsv($handle, [
                        $student->id,
                        $student->name,
                        $cr?->name ?? 'Belum ada rombel',
                        $user?->username ?? $student->nisn,
                        $defaultPassword,
                        $user?->is_active ? 'Aktif' : 'Nonaktif',
                        $user?->must_change_password ? 'Ya' : 'Tidak'
                    ]);
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
