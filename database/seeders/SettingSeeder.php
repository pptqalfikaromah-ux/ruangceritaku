<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'school_name' => "MTs Ma'arif NU 1 Sumpiuh",
            'school_npsn' => '20327663 / 121233020015',
            'school_level' => 'SMP/MTs',
            'school_address' => 'Jl. Raya Kradenan No. 1, Kec. Sumpiuh, Kab. Banyumas, Jawa Tengah 53195',
            'school_phone' => '(0282) 472145',
            'school_email' => 'info@mtsmaarifnu1sumpiuh.sch.id',
            'headmaster_name' => "H. Ahmad Bachjatul 'Ulum, S.Ag., M.Pd.I.",
            'headmaster_nip' => '197508142005011004',
            'app_name' => 'Ruang Ceritaku',
            'theme_color' => '#0d9488',
            'app_footer' => "Ruang Ceritaku - Layanan Bimbingan & Konseling Sekolah | MTs Ma'arif NU 1 Sumpiuh",
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
