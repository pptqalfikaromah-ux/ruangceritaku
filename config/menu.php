<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Navigation Items (Hierarchical Menu & Submenu Structure)
    |--------------------------------------------------------------------------
    */
    'items' => [
        // 1. Beranda / Dashboard
        [
            'type' => 'item',
            'label' => 'Beranda',
            'icon' => 'bi-grid-1x2-fill',
            'route' => 'panel.dashboard',
            'active' => 'panel.dashboard',
            'roles' => ['admin', 'guru_bk'],
        ],

        // 2. Data Induk & Kesiswaan (Dropdown)
        [
            'type' => 'dropdown',
            'id' => 'menuDataInduk',
            'label' => 'Data Induk & Kesiswaan',
            'icon' => 'bi-folder2-open',
            'roles' => ['admin', 'guru_bk'],
            'children' => [
                [
                    'label' => 'Data Murid',
                    'icon' => 'bi-people',
                    'route' => 'panel.murid.index',
                    'active' => 'panel.murid.*',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Data Rombel',
                    'icon' => 'bi-door-open',
                    'route' => 'panel.rombel.index',
                    'active' => 'panel.rombel.*',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Tahun Ajaran & Semester',
                    'icon' => 'bi-calendar3',
                    'route' => 'panel.tahun-ajaran.index',
                    'active' => 'panel.tahun-ajaran.*',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Penugasan Wali Kelas',
                    'icon' => 'bi-person-check',
                    'route' => 'panel.penugasan-wali-kelas.index',
                    'active' => 'panel.penugasan-wali-kelas.*',
                    'roles' => ['admin'],
                ],

                [
                    'label' => 'Data Wali Kelas',
                    'icon' => 'bi-person-video3',
                    'route' => 'panel.wali-kelas.index',
                    'active' => 'panel.wali-kelas.*',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Data Guru BK',
                    'icon' => 'bi-person-badge',
                    'route' => 'panel.guru-bk.index',
                    'active' => 'panel.guru-bk.*',
                    'roles' => ['admin'],
                ],
            ],
        ],

        // 3. Layanan Konseling & Bimbingan (Dropdown)
        [
            'type' => 'dropdown',
            'id' => 'menuKonseling',
            'label' => 'Layanan Konseling',
            'icon' => 'bi-chat-heart',
            'badge_key' => 'counseling_and_ims',
            'roles' => ['admin', 'guru_bk'],
            'children' => [
                [
                    'label' => 'Agenda Konseling',
                    'icon' => 'bi-calendar2-check',
                    'route' => 'panel.konseling.index',
                    'active' => 'panel.konseling.*',
                    'badge_key' => 'counseling',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Instrumen IMS',
                    'icon' => 'bi-clipboard2-check',
                    'route' => 'panel.ims.master',
                    'active' => 'panel.ims.*',
                    'badge_key' => 'ims',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Pamflet Kreativitas Siswa',
                    'icon' => 'bi-images',
                    'route' => 'panel.pamflet-kreatifitas.index',
                    'active' => 'panel.pamflet-kreatifitas.*',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Program BK (RPL)',
                    'icon' => 'bi-calendar-week',
                    'route' => 'panel.program-bk.index',
                    'active' => 'panel.program-bk.*',
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Master Layanan BK',
                    'icon' => 'bi-collection',
                    'route' => 'panel.layanan.index',
                    'active' => 'panel.layanan.*',
                    'roles' => ['admin', 'guru_bk'],
                ],
            ],
        ],

        // 4. Pengaduan & Penanganan Kasus (Dropdown)
        [
            'type' => 'dropdown',
            'id' => 'menuPengaduan',
            'label' => 'Pengaduan & Kasus',
            'icon' => 'bi-shield-exclamation',
            'badge_key' => 'all_complaints',
            'roles' => ['admin', 'guru_bk'],
            'children' => [
                [
                    'label' => 'Pengaduan Siswa',
                    'icon' => 'bi-megaphone',
                    'route' => 'panel.pengaduan.index',
                    'active' => 'panel.pengaduan.*',
                    'badge_key' => 'complaints',
                    'danger_alert' => true,
                    'roles' => ['admin', 'guru_bk'],
                ],
                [
                    'label' => 'Aduan Kasus Wali Kelas',
                    'icon' => 'bi-flag',
                    'route' => 'panel.aduan-walikelas.index',
                    'active' => 'panel.aduan-walikelas.*',
                    'badge_key' => 'homeroom_complaints',
                    'roles' => ['admin', 'guru_bk'],
                ],
            ],
        ],

        // 5. Laporan & Rekapitulasi (Single Item)
        [
            'type' => 'item',
            'label' => 'Laporan & Rekapitulasi',
            'icon' => 'bi-file-earmark-bar-graph',
            'route' => 'panel.laporan.index',
            'active' => 'panel.laporan.*',
            'roles' => ['admin', 'guru_bk'],
        ],

        // 6. Administrasi & Pengaturan Sistem (Dropdown)
        [
            'type' => 'dropdown',
            'id' => 'menuAdministrasi',
            'label' => 'Administrasi & Sistem',
            'icon' => 'bi-sliders2',
            'roles' => ['admin', 'guru_bk'],
            'children' => [
                [
                    'label' => 'Pengumuman Madrasah',
                    'icon' => 'bi-broadcast',
                    'route' => 'panel.pengumuman.index',
                    'active' => 'panel.pengumuman.*',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Manajemen Akun',
                    'icon' => 'bi-shield-lock',
                    'route' => 'panel.akun.index',
                    'active' => 'panel.akun.*',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Pengaturan Madrasah',
                    'icon' => 'bi-gear',
                    'route' => 'panel.setting.index',
                    'active' => 'panel.setting.index',
                    'roles' => ['admin'],
                ],
                [
                    'label' => 'Peta Situs',
                    'icon' => 'bi-map',
                    'route' => 'panel.peta-situs',
                    'active' => 'panel.peta-situs',
                    'roles' => ['admin', 'guru_bk'],
                ],
            ],
        ],
    ],
];
