<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentCsvService
{
    /**
     * Download template CSV untuk import data murid
     */
    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'template_import_murid.csv';

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            // Menulis BOM agar UTF-8 terbaca dengan benar di Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'nisn',
                'nis',
                'nama',
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'agama',
                'alamat',
                'nama_wali',
                'no_hp_wali'
            ]);

            // Baris contoh 1
            fputcsv($handle, [
                '0081234567',
                '202401',
                'Ahmad Fauzan',
                'L',
                'Jakarta',
                '2008-05-14',
                'Islam',
                'Jl. Melati No. 12 RT 01/02',
                'Budi Santoso',
                '081234567890'
            ]);

            // Baris contoh 2
            fputcsv($handle, [
                '0081234568',
                '202402',
                'Siti Nurhaliza',
                'P',
                'Bandung',
                '2008-09-20',
                'Islam',
                'Jl. Mawar No. 45',
                'Hasan Basri',
                '081987654321'
            ]);

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import data murid dari file CSV
     */
    public function import(UploadedFile $file, ?int $classroomId = null): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return [
                'success_count' => 0,
                'failed_rows' => [['row' => 0, 'nisn' => '-', 'error' => 'Gagal membuka file CSV.']],
            ];
        }

        $activeYear = active_academic_year();
        $classroom = $classroomId ? Classroom::find($classroomId) : null;

        $header = fgetcsv($handle);
        // Bersihkan UTF-8 BOM pada kolom pertama jika ada
        if ($header && isset($header[0])) {
            $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        }

        $successCount = 0;
        $failedRows = [];
        $rowNumber = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // Abaikan baris kosong
                if (empty(array_filter($row))) {
                    continue;
                }

                $nisn = trim($row[0] ?? '');
                $nis = trim($row[1] ?? '') ?: null;
                $name = trim($row[2] ?? '');
                $gender = strtoupper(trim($row[3] ?? ''));
                $birthPlace = trim($row[4] ?? '') ?: null;
                $birthDate = trim($row[5] ?? '') ?: null;
                $religion = trim($row[6] ?? '') ?: null;
                $address = trim($row[7] ?? '') ?: null;
                $guardianName = trim($row[8] ?? '') ?: null;
                $guardianPhone = trim($row[9] ?? '') ?: null;

                if (empty($nisn) || empty($name)) {
                    $failedRows[] = [
                        'row' => $rowNumber,
                        'nisn' => $nisn ?: '(Kosong)',
                        'error' => 'NISN dan Nama wajib diisi.',
                    ];
                    continue;
                }

                if (!in_array($gender, ['L', 'P'])) {
                    $failedRows[] = [
                        'row' => $rowNumber,
                        'nisn' => $nisn,
                        'error' => "Jenis kelamin harus 'L' atau 'P'.",
                    ];
                    continue;
                }

                // Cek duplikasi NISN
                $existing = Student::where('nisn', $nisn)->first();
                if ($existing) {
                    $failedRows[] = [
                        'row' => $rowNumber,
                        'nisn' => $nisn,
                        'error' => "NISN {$nisn} sudah terdaftar pada sistem.",
                    ];
                    continue;
                }

                // Format tanggal lahir jika ada
                if ($birthDate) {
                    $timestamp = strtotime($birthDate);
                    $birthDate = $timestamp ? date('Y-m-d', $timestamp) : null;
                }

                $student = Student::create([
                    'nisn' => $nisn,
                    'nis' => $nis,
                    'name' => $name,
                    'gender' => $gender,
                    'birth_place' => $birthPlace,
                    'birth_date' => $birthDate,
                    'religion' => $religion,
                    'address' => $address,
                    'guardian_name' => $guardianName,
                    'guardian_phone' => $guardianPhone,
                    'status' => 'aktif',
                ]);

                // Hubungkan ke rombel jika ditentukan
                if ($classroom && $activeYear) {
                    $student->classrooms()->syncWithoutDetaching([
                        $classroom->id => ['academic_year_id' => $activeYear->id],
                    ]);
                }

                $successCount++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $failedRows[] = [
                'row' => $rowNumber,
                'nisn' => '-',
                'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ];
        } finally {
            fclose($handle);
        }

        return [
            'success_count' => $successCount,
            'failed_rows' => $failedRows,
        ];
    }

    /**
     * Export data murid ke CSV
     */
    public function export(array $filters = []): StreamedResponse
    {
        $filename = 'data_murid_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($filters) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'NISN',
                'NIS',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Rombel Saat Ini',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Agama',
                'Alamat',
                'Nama Wali',
                'No HP Wali',
                'Status',
                'Memiliki Akun Login'
            ]);

            $query = Student::with(['classrooms'])->filter($filters);

            $query->chunk(200, function ($students) use ($handle) {
                foreach ($students as $student) {
                    $currentCr = $student->current_classroom;
                    fputcsv($handle, [
                        $student->id,
                        $student->nisn,
                        $student->nis ?? '-',
                        $student->name,
                        $student->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                        $currentCr?->name ?? 'Belum ada rombel',
                        $student->birth_place ?? '-',
                        $student->birth_date?->format('Y-m-d') ?? '-',
                        $student->religion ?? '-',
                        $student->address ?? '-',
                        $student->guardian_name ?? '-',
                        $student->guardian_phone ?? '-',
                        ucfirst($student->status),
                        $student->user_id ? 'Ya' : 'Belum'
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
