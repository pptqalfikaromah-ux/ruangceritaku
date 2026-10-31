<?php

namespace Database\Seeders;

use App\Models\GuidanceField;
use App\Models\ServiceFunction;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class MasterServiceSeeder extends Seeder
{
    public function run(): void
    {
        // 9 Layanan BK
        $services = [
            [
                'code' => 'LAY-01',
                'name' => 'Layanan Orientasi',
                'category' => 'layanan',
                'description' => 'Layanan bimbingan untuk membantu peserta didik memahami lingkungan baru, terutama lingkungan sekolah dan obyek-obyek yang dipelajari.',
            ],
            [
                'code' => 'LAY-02',
                'name' => 'Layanan Informasi',
                'category' => 'layanan',
                'description' => 'Layanan yang membantu peserta didik menerima dan memahami berbagai informasi diri, sosial, belajar, pergaulan, karier, dan pendidikan lanjutan.',
            ],
            [
                'code' => 'LAY-04',
                'name' => 'Layanan Penguasaan Konten',
                'category' => 'layanan',
                'description' => 'Layanan bimbingan dan konseling yang membantu peserta didik menguasai kemampuan atau kompetensi tertentu melalui kegiatan belajar.',
            ],
            [
                'code' => 'LAY-05',
                'name' => 'Layanan Konseling Perorangan',
                'category' => 'layanan',
                'description' => 'Layanan tatap muka individual antara Guru BK dan konseli untuk mengentaskan masalah pribadi yang sedang dialami.',
            ],
            [
                'code' => 'LAY-06',
                'name' => 'Layanan Bimbingan Kelompok',
                'category' => 'layanan',
                'description' => 'Layanan bimbingan yang memanfaatkan dinamika kelompok untuk membahas topik-topik umum yang berguna bagi pengembangan diri peserta didik.',
            ],
            [
                'code' => 'LAY-07',
                'name' => 'Layanan Konseling Kelompok',
                'category' => 'layanan',
                'description' => 'Layanan konseling yang memanfaatkan dinamika kelompok untuk membantu mengentaskan masalah pribadi masing-masing anggota kelompok.',
            ],
            [
                'code' => 'LAY-08',
                'name' => 'Layanan Konsultasi',
                'category' => 'layanan',
                'description' => 'Layanan yang memungkinkan seseorang (konsulti/guru mata pelajaran/wali kelas/orang tua) memperoleh wawasan, pemahaman, dan cara-cara yang perlu dilaksanakan dalam membantu pihak ketiga (peserta didik).',
            ],
            [
                'code' => 'LAY-09',
                'name' => 'Layanan Mediasi',
                'category' => 'layanan',
                'description' => 'Layanan yang membantu peserta didik menyelesaikan perselisihan atau ketidakcocokan hubungan antar siswa agar tercipta hubungan yang positif dan kondusif.',
            ],
        ];

        foreach ($services as $srv) {
            ServiceType::updateOrCreate(['code' => $srv['code']], $srv);
        }

        // 6 Kegiatan Pendukung
        $supportings = [
            [
                'code' => 'DUK-01',
                'name' => 'Aplikasi Instrumentasi',
                'category' => 'pendukung',
                'description' => 'Kegiatan pengumpulan data dan keterangan tentang peserta didik dan lingkungannya melalui instrumen tes maupun non-tes (AUM, DCM, sosiometri, angket).',
            ],
            [
                'code' => 'DUK-02',
                'name' => 'Himpunan Data',
                'category' => 'pendukung',
                'description' => 'Kegiatan menghimpun seluruh data dan keterangan yang relevan dengan keperluan peserta didik secara sistematis, komprehensif, dan rahasia.',
            ],
            [
                'code' => 'DUK-03',
                'name' => 'Konferensi Kasus',
                'category' => 'pendukung',
                'description' => 'Kegiatan membahas permasalahan peserta didik dalam pertemuan khusus yang dihadiri oleh pihak-pihak terkait (wali kelas, kepala sekolah, orang tua).',
            ],
            [
                'code' => 'DUK-04',
                'name' => 'Kunjungan Rumah (Home Visit)',
                'category' => 'pendukung',
                'description' => 'Kegiatan mendatangi tempat tinggal peserta didik untuk memperoleh keterangan lebih mendalam dan membangun kerja sama dengan orang tua.',
            ],
            [
                'code' => 'DUK-05',
                'name' => 'Tampilan Kepustakaan',
                'category' => 'pendukung',
                'description' => 'Kegiatan menyediakan berbagai bahan pustaka, artikel, dan referensi bacaan yang dapat dimanfaatkan peserta didik untuk pengembangan diri.',
            ],
            [
                'code' => 'DUK-06',
                'name' => 'Alih Tangan Kasus (Referral)',
                'category' => 'pendukung',
                'description' => 'Kegiatan memindahkan penanganan masalah peserta didik kepada pihak lain yang lebih berwenang dan ahli (psikolog, psikiater, dokter, kepolisian).',
            ],
        ];

        foreach ($supportings as $spt) {
            ServiceType::updateOrCreate(['code' => $spt['code']], $spt);
        }

        // 4 Bidang Bimbingan
        $fields = [
            [
                'code' => 'BID-01',
                'name' => 'Pribadi',
                'description' => 'Membantu peserta didik memantapkan kepribadian, potensi diri, dan bakat minat serta mengatasi hambatan emosi dan spiritual.',
            ],
            [
                'code' => 'BID-02',
                'name' => 'Sosial',
                'description' => 'Membantu peserta didik mengenal dan berhubungan dengan lingkungan sosial yang berlandaskan budi pekerti luhur dan tanggung jawab.',
            ],
            [
                'code' => 'BID-03',
                'name' => 'Belajar',
                'description' => 'Membantu peserta didik mengembangkan kemampuan belajar efektif, persiapan ujian, dan penyesuaian kurikulum.',
            ],
            [
                'code' => 'BID-04',
                'name' => 'Karier',
                'description' => 'Membantu peserta didik mengenali bakat, merencanakan kelanjutan studi, dan mengenal dunia kerja masa depan.',
            ],
        ];

        foreach ($fields as $fld) {
            GuidanceField::updateOrCreate(['code' => $fld['code']], $fld);
        }

        // 4 Fungsi Layanan
        $functions = [
            [
                'code' => 'FNG-01',
                'name' => 'Pemahaman',
                'description' => 'Menghasilkan pemahaman tentang diri peserta didik beserta lingkungannya secara utuh.',
            ],
            [
                'code' => 'FNG-02',
                'name' => 'Pemahaman dan Pencegahan',
                'description' => 'Menghasilkan tercegahnya atau terhindarnya peserta didik dari berbagai permasalahan yang dapat mengganggu perkembangannya.',
            ],
            [
                'code' => 'FNG-03',
                'name' => 'Pemeliharaan dan Pengembangan',
                'description' => 'Menghasilkan terpelihara dan terkembangnya berbagai potensi dan kondisi positif peserta didik secara berkelanjutan.',
            ],
            [
                'code' => 'FNG-04',
                'name' => 'Pengentasan',
                'description' => 'Menghasilkan terentaskannya atau teratasinya berbagai permasalahan yang dialami oleh peserta didik.',
            ],
        ];

        foreach ($functions as $fnc) {
            ServiceFunction::updateOrCreate(['code' => $fnc['code']], $fnc);
        }
    }
}
