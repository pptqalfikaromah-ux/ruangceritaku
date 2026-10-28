<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\Panel\AcademicYearController;
use App\Http\Controllers\Panel\AccountController;
use App\Http\Controllers\Panel\BkProgramController;
use App\Http\Controllers\Panel\ClassroomController;
use App\Http\Controllers\Panel\ComplaintController;
use App\Http\Controllers\Panel\CounselingRequestController;
use App\Http\Controllers\Panel\CounselorAssignmentController;
use App\Http\Controllers\Panel\CounselorController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\HomeroomAssignmentController;
use App\Http\Controllers\Panel\HomeroomComplaintController;
use App\Http\Controllers\Panel\HomeroomTeacherController;
use App\Http\Controllers\Panel\ImsController;
use App\Http\Controllers\Panel\MasterServiceController;
use App\Http\Controllers\Panel\ReportController;
use App\Http\Controllers\Panel\SettingController;
use App\Http\Controllers\Panel\StudentController;
use App\Http\Controllers\Student\StudentComplaintController;
use App\Http\Controllers\Student\StudentCounselingController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentImsController;
use App\Http\Controllers\Panel\AnnouncementController;
use App\Http\Controllers\Panel\PamphletModerationController;
use App\Http\Controllers\Student\StudentPamphletController;
use App\Http\Controllers\WaliKelas\WaliKelasAduanController;
use App\Http\Controllers\WaliKelas\WaliKelasDashboardController;
use App\Http\Controllers\WaliKelas\WaliKelasMonitoringController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page Utama Ruang Ceritaku
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// Autentikasi Manual (Auth Facade) dengan Pintu Masuk Masing-Masing
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
    Route::get('/login/wali-kelas', [LoginController::class, 'showWaliKelasLoginForm'])->name('login.wali-kelas');
    Route::get('/login/murid', [LoginController::class, 'showMuridLoginForm'])->name('login.murid');
    Route::post('/login', [LoginController::class, 'login']);

    // Pendaftaran Khusus Murid Baru
    Route::get('/daftar-murid', [StudentRegisterController::class, 'showRegistrationForm'])->name('register.murid');
    Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/daftar-murid', [StudentRegisterController::class, 'register'])->name('register.murid.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Panel Admin & Guru BK
| Prefix: /panel | Middleware: auth, role:admin,guru_bk, must.change.password
|--------------------------------------------------------------------------
*/
Route::prefix('panel')->name('panel.')->middleware(['auth', 'role:admin,guru_bk', 'must.change.password'])->group(function () {

    // Ganti Password (Dapat diakses meski must_change_password aktif)
    Route::get('/ubah-password', [SettingController::class, 'changePassword'])->name('password.change');
    Route::put('/ubah-password', [SettingController::class, 'updatePassword'])->name('password.update');

    // Dashboard (Beranda Personal & Peta Situs)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/beranda', [DashboardController::class, 'index'])->name('beranda');
    Route::get('/peta-situs', [DashboardController::class, 'petaSitus'])->name('peta-situs');
    Route::post('/notifikasi/mark-all-read', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');

    // Profil Saya
    Route::get('/profil', [SettingController::class, 'profile'])->name('setting.profile');
    Route::put('/profil', [SettingController::class, 'updateProfile'])->name('setting.profile.update');

    // 1. Tahun Ajaran & Semester
    Route::get('/tahun-ajaran', [AcademicYearController::class, 'index'])->name('tahun-ajaran.index');
    Route::middleware('role:admin')->group(function () {
        Route::post('/tahun-ajaran', [AcademicYearController::class, 'store'])->name('tahun-ajaran.store');
        Route::put('/tahun-ajaran/{tahun_ajaran}', [AcademicYearController::class, 'update'])->name('tahun-ajaran.update');
        Route::delete('/tahun-ajaran/{tahun_ajaran}', [AcademicYearController::class, 'destroy'])->name('tahun-ajaran.destroy');
        Route::post('/semester/{semester}/aktifkan', [AcademicYearController::class, 'activateSemester'])->name('semester.activate');
    });

    // 2. Data Rombel
    Route::get('/rombel', [ClassroomController::class, 'index'])->name('rombel.index');
    Route::get('/rombel/{rombel}', [ClassroomController::class, 'show'])->name('rombel.show');
    Route::middleware('role:admin')->group(function () {
        Route::get('/rombel-tambah/baru', [ClassroomController::class, 'create'])->name('rombel.create');
        Route::post('/rombel', [ClassroomController::class, 'store'])->name('rombel.store');
        Route::get('/rombel/{rombel}/edit', [ClassroomController::class, 'edit'])->name('rombel.edit');
        Route::put('/rombel/{rombel}', [ClassroomController::class, 'update'])->name('rombel.update');
        Route::delete('/rombel/{rombel}', [ClassroomController::class, 'destroy'])->name('rombel.destroy');
        Route::post('/rombel/{rombel}/murid', [ClassroomController::class, 'addStudent'])->name('rombel.student.add');
        Route::delete('/rombel/{rombel}/murid/{student}', [ClassroomController::class, 'removeStudent'])->name('rombel.student.remove');
    });

    // 3. Data Murid
    Route::get('/murid', [StudentController::class, 'index'])->name('murid.index');
    Route::middleware('role:admin')->group(function () {
        Route::get('/murid-tambah/baru', [StudentController::class, 'create'])->name('murid.create');
        Route::post('/murid', [StudentController::class, 'store'])->name('murid.store');
        Route::get('/murid/{murid}/edit', [StudentController::class, 'edit'])->name('murid.edit');
        Route::put('/murid/{murid}', [StudentController::class, 'update'])->name('murid.update');
        Route::delete('/murid/{murid}', [StudentController::class, 'destroy'])->name('murid.destroy');
        Route::get('/murid-csv/template', [StudentController::class, 'downloadTemplate'])->name('murid.template');
        Route::post('/murid-csv/import', [StudentController::class, 'import'])->name('murid.import');
        Route::get('/murid-csv/export', [StudentController::class, 'export'])->name('murid.export');
    });
    Route::get('/murid/{murid}', [StudentController::class, 'show'])->name('murid.show');

    // 4. Data Guru BK (Khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/guru-bk', [CounselorController::class, 'index'])->name('guru-bk.index');
        Route::get('/guru-bk/create', [CounselorController::class, 'create'])->name('guru-bk.create');
        Route::post('/guru-bk', [CounselorController::class, 'store'])->name('guru-bk.store');
        Route::get('/guru-bk/{guru_bk}/edit', [CounselorController::class, 'edit'])->name('guru-bk.edit');
        Route::put('/guru-bk/{guru_bk}', [CounselorController::class, 'update'])->name('guru-bk.update');
        Route::delete('/guru-bk/{guru_bk}', [CounselorController::class, 'destroy'])->name('guru-bk.destroy');
    });

    // 5. Penugasan Guru BK (Dinonaktifkan: semua akun BK dapat mengakses seluruh rombel dan murid)
    Route::get('/penugasan', function () {
        return redirect()->route('panel.dashboard')->with('info', 'Penugasan rombel telah dinonaktifkan. Semua akun Guru BK dapat membina dan mengakses seluruh rombel serta murid.');
    })->name('penugasan.index');

    // 5b. Data Wali Kelas & Penugasan Wali Kelas (Khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::resource('wali-kelas', HomeroomTeacherController::class);
        Route::get('/penugasan-wali-kelas', [HomeroomAssignmentController::class, 'index'])->name('penugasan-wali-kelas.index');
        Route::post('/penugasan-wali-kelas', [HomeroomAssignmentController::class, 'store'])->name('penugasan-wali-kelas.store');
        Route::delete('/penugasan-wali-kelas/{penugasanWaliKela}', [HomeroomAssignmentController::class, 'destroy'])->name('penugasan-wali-kelas.destroy');
    });

    // 6. Master Layanan BK
    Route::get('/layanan', [MasterServiceController::class, 'index'])->name('layanan.index');
    Route::middleware('role:admin')->group(function () {
        Route::post('/layanan', [MasterServiceController::class, 'storeService'])->name('layanan.store');
        Route::put('/layanan/{service}', [MasterServiceController::class, 'updateService'])->name('layanan.update');
    });

    // 7. Instrumen Masalah Siswa (IMS 180 Butir)
    Route::get('/ims/master', [ImsController::class, 'master'])->name('ims.master');
    Route::put('/ims/statement/{statement}', [ImsController::class, 'updateStatement'])->name('ims.update');
    Route::post('/ims/statement/{statement}/toggle', [ImsController::class, 'toggleStatement'])->name('ims.toggle');
    Route::get('/ims/monitoring', [ImsController::class, 'monitoring'])->name('ims.monitoring');
    Route::get('/ims/assessment/{assessment}', [ImsController::class, 'showAssessment'])->name('ims.show');
    Route::get('/ims/rekap', [ImsController::class, 'rekap'])->name('ims.rekap');

    // 8. Monitoring, Persetujuan & Penjadwalan Konseling
    Route::get('/konseling', [CounselingRequestController::class, 'index'])->name('konseling.index');
    Route::get('/konseling/{konseling}', [CounselingRequestController::class, 'show'])->name('konseling.show');
    Route::put('/konseling/{konseling}/status', [CounselingRequestController::class, 'updateStatus'])->name('konseling.status');
    Route::post('/konseling/{konseling}/schedule', [CounselingRequestController::class, 'schedule'])->name('konseling.schedule');
    Route::post('/konseling/{konseling}/complete', [CounselingRequestController::class, 'complete'])->name('konseling.complete');
    Route::get('/konseling/{konseling}/cetak-kartu', [CounselingRequestController::class, 'printCard'])->name('konseling.printCard');

    // 9. Layanan Pengaduan & Perlindungan Anak
    Route::get('/pengaduan', [ComplaintController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{pengaduan}', [ComplaintController::class, 'show'])->name('pengaduan.show');
    Route::put('/pengaduan/{pengaduan}/status', [ComplaintController::class, 'updateStatus'])->name('pengaduan.status');

    // 9b. Aduan Kasus Murid dari Wali Kelas
    Route::get('/aduan-walikelas', [HomeroomComplaintController::class, 'index'])->name('aduan-walikelas.index');
    Route::get('/aduan-walikelas/{aduanWalikela}', [HomeroomComplaintController::class, 'show'])->name('aduan-walikelas.show');
    Route::put('/aduan-walikelas/{aduanWalikela}', [HomeroomComplaintController::class, 'update'])->name('aduan-walikelas.update');

    // 10. Program BK & RPL
    Route::get('/program-bk', [BkProgramController::class, 'index'])->name('program-bk.index');
    Route::post('/program-bk', [BkProgramController::class, 'storeProgram'])->name('program-bk.store');
    Route::put('/program-bk/{program}', [BkProgramController::class, 'updateProgram'])->name('program-bk.update');
    Route::delete('/program-bk/{program}', [BkProgramController::class, 'destroyProgram'])->name('program-bk.destroy');
    Route::get('/program-bk/rpl/create', [BkProgramController::class, 'createRpl'])->name('program-bk.rpl.create');
    Route::post('/program-bk/rpl', [BkProgramController::class, 'storeRpl'])->name('program-bk.rpl.store');
    Route::get('/program-bk/rpl/{rpl}', [BkProgramController::class, 'showRpl'])->name('program-bk.rpl.show');
    Route::delete('/program-bk/rpl/{rpl}', [BkProgramController::class, 'destroyRpl'])->name('program-bk.rpl.destroy');

    // 11. Laporan & Rekapitulasi
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [ReportController::class, 'print'])->name('laporan.print');

    // 12. Manajemen Akun (Khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/akun', [AccountController::class, 'index'])->name('akun.index');
        Route::post('/akun', [AccountController::class, 'store'])->name('akun.store');
        Route::put('/akun/{akun}', [AccountController::class, 'update'])->name('akun.update');
        Route::post('/akun/{akun}/toggle', [AccountController::class, 'toggleStatus'])->name('akun.toggle-status');
        Route::post('/akun/{akun}/reset-password', [AccountController::class, 'resetPassword'])->name('akun.reset-password');
        Route::delete('/akun/{akun}', [AccountController::class, 'destroy'])->name('akun.destroy');
        Route::post('/akun/generate-murid', [AccountController::class, 'generateStudentAccounts'])->name('akun.generate-students');
        Route::get('/akun/export-kredensial', [AccountController::class, 'exportStudentCredentials'])->name('akun.export-credentials');
    });

    // 12b. Pamflet Kreativitas Siswa (Moderasi)
    Route::get('/pamflet-kreatifitas', [PamphletModerationController::class, 'index'])->name('pamflet-kreatifitas.index');
    Route::post('/pamflet-kreatifitas/{pamphlet}/approve', [PamphletModerationController::class, 'approve'])->name('pamflet-kreatifitas.approve');
    Route::post('/pamflet-kreatifitas/{pamphlet}/reject', [PamphletModerationController::class, 'reject'])->name('pamflet-kreatifitas.reject');
    Route::post('/pamflet-kreatifitas/{pamphlet}/toggle', [PamphletModerationController::class, 'togglePublish'])->name('pamflet-kreatifitas.toggle');
    Route::delete('/pamflet-kreatifitas/{pamphlet}', [PamphletModerationController::class, 'destroy'])->name('pamflet-kreatifitas.destroy');

    // 13. Pengaturan Madrasah & Aplikasi (Khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/setting', [SettingController::class, 'updateSettings'])->name('setting.update');

        // 14. Pengumuman Madrasah
        Route::resource('pengumuman', AnnouncementController::class)->except(['create', 'show', 'edit']);
    });
});

/*
|--------------------------------------------------------------------------
| Portal Murid (Student Area)
| Prefix: /murid | Middleware: auth, role:murid
|--------------------------------------------------------------------------
*/
Route::prefix('murid')->name('murid.')->middleware(['auth', 'role:murid'])->group(function () {
    // Beranda Murid
    Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard.index');

    // 1. Hub Utama Pengajuan Konseling (Pilihan: Konseling Pribadi vs Konseling Melalui IMS)
    Route::get('/ajukan-konseling', [StudentCounselingController::class, 'ajukan'])->name('konseling.ajukan');

    // 1b. Instrumen Masalah Siswa (IMS) - Alur Berjenjang (Intent)
    Route::get('/ims', [StudentImsController::class, 'index'])->name('ims.index'); // Halaman 1: Pilih Layanan
    Route::get('/ims/layanan/{service}', [StudentImsController::class, 'selectField'])->name('ims.service'); // Halaman 2: Pilih Bidang
    Route::get('/ims/layanan/{service}/bidang/{field}', [StudentImsController::class, 'showInstrument'])->name('ims.instrument'); // Halaman 3: Instrumen 5 Butir + Catatan Lebar
    Route::post('/ims/layanan/{service}/bidang/{field}', [StudentImsController::class, 'saveInstrument'])->name('ims.saveInstrument'); // Simpan Jawaban & Catatan
    Route::get('/ims/jadwal-konseling', [StudentImsController::class, 'scheduleCounselingForm'])->name('ims.counseling.create'); // Form Jadwal Khusus Masalah IMS
    Route::post('/ims/jadwal-konseling', [StudentImsController::class, 'storeCounselingFromIms'])->name('ims.counseling.store'); // Simpan Pengajuan Jadwal IMS
    Route::post('/ims/draft', [StudentImsController::class, 'saveDraft'])->name('ims.saveDraft');
    Route::post('/ims/submit', [StudentImsController::class, 'submit'])->name('ims.submit');

    // 2. Pengajuan Konseling Mandiri / Pribadi
    Route::get('/konseling', [StudentCounselingController::class, 'index'])->name('konseling.index');
    Route::get('/konseling/tambah', [StudentCounselingController::class, 'create'])->name('konseling.create');
    Route::get('/konseling/pribadi', [StudentCounselingController::class, 'create'])->name('konseling.pribadi');
    Route::post('/konseling', [StudentCounselingController::class, 'store'])->name('konseling.store');
    Route::get('/konseling/{konseling}', [StudentCounselingController::class, 'show'])->name('konseling.show');
    Route::get('/konseling/{konseling}/cetak-kartu', [StudentCounselingController::class, 'printCard'])->name('konseling.printCard');
    Route::post('/konseling/{konseling}/konfirmasi', [StudentCounselingController::class, 'confirmSchedule'])->name('konseling.confirmSchedule');
    Route::post('/konseling/{konseling}/reschedule', [StudentCounselingController::class, 'requestReschedule'])->name('konseling.requestReschedule');

    // 3. Jalur Pengaduan Aman & Perlindungan Diri
    Route::get('/pengaduan', [StudentComplaintController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/tambah', [StudentComplaintController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [StudentComplaintController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{pengaduan}', [StudentComplaintController::class, 'show'])->name('pengaduan.show');

    // 4. Pamflet Kreativitas Murid (Upload & Galeri Saya)
    Route::get('/pamflet', [StudentPamphletController::class, 'index'])->name('pamflet.index');
    Route::get('/pamflet/tambah', [StudentPamphletController::class, 'create'])->name('pamflet.create');
    Route::post('/pamflet', [StudentPamphletController::class, 'store'])->name('pamflet.store');
    Route::delete('/pamflet/{pamphlet}', [StudentPamphletController::class, 'destroy'])->name('pamflet.destroy');
});

/*
|--------------------------------------------------------------------------
| Portal Wali Kelas (Homeroom Teacher Area)
| Prefix: /walikelas | Middleware: auth, role:wali_kelas
|--------------------------------------------------------------------------
*/
Route::prefix('walikelas')->name('walikelas.')->middleware(['auth', 'role:wali_kelas'])->group(function () {
    // Beranda Wali Kelas
    Route::get('/', [WaliKelasDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [WaliKelasDashboardController::class, 'index'])->name('dashboard.index');

    // Monitoring Konseling dialihkan ke Dashboard
    Route::redirect('/monitoring', '/walikelas/dashboard')->name('monitoring.index');

    // Fitur Aduan Kasus Murid untuk Ditindaklanjuti Guru BK
    Route::get('/aduan', [WaliKelasAduanController::class, 'index'])->name('aduan.index');
    Route::get('/aduan/tambah', [WaliKelasAduanController::class, 'create'])->name('aduan.create');
    Route::post('/aduan', [WaliKelasAduanController::class, 'store'])->name('aduan.store');
    Route::get('/aduan/{aduan}', [WaliKelasAduanController::class, 'show'])->name('aduan.show');

    // Profil Wali Kelas
    Route::get('/profil', [\App\Http\Controllers\WaliKelas\WaliKelasProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [\App\Http\Controllers\WaliKelas\WaliKelasProfileController::class, 'update'])->name('profile.update');
});
