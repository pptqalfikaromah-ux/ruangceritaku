<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselingGameController extends Controller
{
    /**
     * Tampilkan Game Konseling di Panel Admin / Guru BK
     */
    public function panelIndex(): View
    {
        return view('game.counseling-hub', [
            'layout' => 'layouts.panel',
            'roleType' => 'panel',
            'roleLabel' => 'Panel Admin & Guru BK',
            'cardDecks' => $this->getCardDecks(),
            'dilemmas' => $this->getDilemmaScenarios(),
        ]);
    }

    /**
     * Tampilkan Game Konseling di Portal Wali Kelas
     */
    public function waliKelasIndex(): View
    {
        return view('game.counseling-hub', [
            'layout' => 'layouts.walikelas',
            'roleType' => 'walikelas',
            'roleLabel' => 'Portal Wali Kelas',
            'cardDecks' => $this->getCardDecks(),
            'dilemmas' => $this->getDilemmaScenarios(),
        ]);
    }

    /**
     * Tampilkan Game Konseling di Portal Murid
     */
    public function studentIndex(): View
    {
        return view('game.counseling-hub', [
            'layout' => 'layouts.student',
            'roleType' => 'murid',
            'roleLabel' => 'Portal Murid',
            'cardDecks' => $this->getCardDecks(),
            'dilemmas' => $this->getDilemmaScenarios(),
        ]);
    }

    /**
     * Bank Kartu Ruang Rasa (4 Kategori)
     */
    private function getCardDecks(): array
    {
        return [
            'feelings' => [
                'name' => 'Perasaan & Suasana Hati',
                'color' => '#f59e0b',
                'badge' => '💛 Emosi Diri',
                'cards' => [
                    'Kapan terakhir kali kamu merasa sangat bangga pada dirimu sendiri? Apa penyebabnya?',
                    'Jika emosimu hari ini adalah sebuah cuaca, apakah hari ini cerah, berawan, gerimis, atau badai? Mengapa?',
                    'Hal kecil apa yang paling cepat membuat senyummu kembali saat harimu sedang berat?',
                    'Perasaan apa yang saat ini paling sering kamu sembunyikan dari orang lain?',
                    'Jika ada 1 hal yang sedang membuat pikiranmu lelah minggu ini, hal apakah itu?',
                    'Apa satu pujian yang pernah kamu terima dan masih kamu ingat sampai hari ini?',
                    'Kapan kamu merasa paling aman dan tenang menjadi dirimu sendiri apa adanya?',
                    'Hal apa yang biasanya membuatmu merasa tidak percaya diri, dan bagaimana caramu menghibur diri?',
                ],
            ],
            'social' => [
                'name' => 'Pertemanan & Empati',
                'color' => '#0d9488',
                'badge' => '🤝 Sosial & Sahabat',
                'cards' => [
                    'Apa arti seorang sahabat sejati menurut pandanganmu?',
                    'Jika melihat temanmu tiba-tiba diam dan murung seharian, apa kalimat pertama yang akan kamu sampaikan?',
                    'Pernahkah kamu merasa berbeda atau tidak cocok di suatu kelompok? Bagaimana caramu menyikapinya?',
                    'Sebutkan 1 sifat teman yang paling kamu kagumi dan ingin kamu tiru!',
                    'Apa yang biasanya kamu lakukan jika ada teman yang membicarakan keburukan orang lain di depanmu?',
                    'Kebaikan kecil apa yang pernah dilakukan seorang teman kepadamu yang tak pernah kamu lupakan?',
                    'Bagaimana caramu menyampaikan rasa keberatan kepada teman tanpa harus bertengkar atau menyakiti hatinya?',
                    'Jika kamu bisa meminta maaf kepada seseorang di masa lalu tanpa rasa canggung, kepada siapa dan kenapa?',
                ],
            ],
            'goals' => [
                'name' => 'Mimpi & Masa Depan',
                'color' => '#8b5cf6',
                'badge' => '🎯 Cita-Cita & Harapan',
                'cards' => [
                    'Jika tidak ada rasa takut gagal sama sekali, apa impian terbesar yang ingin kamu wujudkan?',
                    'Sebutkan 1 bakat atau kelebihan unikmu yang jarang disadari oleh orang banyak!',
                    'Jika kamu memiliki pintu ajaib yang bisa membawamu ke 10 tahun ke depan, sedang menjadi apakah dirimu?',
                    'Satu kebiasaan baik apa yang ingin kamu mulai latih secara konsisten mulai hari ini?',
                    'Siapa sosok yang paling menginspirasimu dalam belajar dan berjuang? Apa alasannya?',
                    'Apa satu hal yang paling ingin kamu perbaiki dari caramu belajar atau membagi waktu saat ini?',
                    'Jika kamu diberi kesempatan memberikan hadiah terbaik untuk kedua orang tuamu, hadiah apa itu?',
                    'Apa arti kata "sukses" yang sesungguhnya menurut hatimu?',
                ],
            ],
            'coping' => [
                'name' => 'Penyelesaian Masalah & Ketahanan',
                'color' => '#ef4444',
                'badge' => '🛡️ Resolusi & Ketangguhan',
                'cards' => [
                    'Saat rasa marah atau kecewa memuncak, apa hal positif yang paling membantumu meredakannya?',
                    'Pernahkah kamu menghadapi kegagalan yang berat, lalu berhasil bangkit kembali? Bagaimana ceritanya?',
                    'Ketika tugas atau hafalan menumpuk banyak, langkah pertama apa yang kamu lakukan agar tidak panik?',
                    'Jika ada orang yang mengejek atau meremehkan kemampuanmu, apa respon terbaik yang ingin kamu tunjukkan?',
                    'Kepada siapa kamu biasanya bercerita saat masalahmu terasa terlalu berat untuk dipikul sendirian?',
                    'Apa kalimat penyemangat diri (*self-talk*) yang paling sering kamu bisikkan ke hatimu saat lelah?',
                    'Hal apa yang membuatmu sadar bahwa tidak apa-apa jika terkadang kita berbuat kesalahan?',
                    'Bagaimana caramu membedakan antara kritik yang membangun dengan kata-kata yang berniat menjatuhkanmu?',
                ],
            ],
        ];
    }

    /**
     * Skenario Dilema Pilihan Bijak
     */
    private function getDilemmaScenarios(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Teman yang Tersisih di Kelas',
                'scenario' => 'Saat jam istirahat, kamu melihat seorang teman sekelasmu duduk sendirian di meja pojok sambil menatap lantai dengan wajah sedih, sementara teman-teman lain asyik bercanda di luar kelas.',
                'choices' => [
                    [
                        'text' => 'Mendekatinya perlahan, menawarinya camilan, dan mengajaknya mengobrol santai tanpa memaksa menceritakan masalahnya.',
                        'badge' => 'Empati Tinggi 🌟',
                        'feedback' => 'Luar biasa! Kehadiran yang tulus dan tidak memaksa membuat seseorang merasa dihargai dan aman untuk mulai terbuka.'
                    ],
                    [
                        'text' => 'Mengajak beberapa teman lain untuk langsung membawanya ke luar kelas agar ikut bermain.',
                        'badge' => 'Niat Baik Tapi Perlu Hati-Hati ⚠️',
                        'feedback' => 'Niatmu baik, namun orang yang sedang sedih terkadang butuh ruang tenang terlebih dahulu sebelum siap berada di keramaian.'
                    ],
                    [
                        'text' => 'Membiarkannya karena menganggap dia sedang ingin sendiri dan tidak ingin mengganggu.',
                        'badge' => 'Kurang Peka ❄️',
                        'feedback' => 'Terkadang seseorang diam bukan karena ingin ditinggal, melainkan menunggu ada satu orang yang cukup peduli untuk menyapa.'
                    ],
                ]
            ],
            [
                'id' => 2,
                'title' => 'Grup Chat & Gosip Negatif',
                'scenario' => 'Di grup obrolan kelas, beberapa teman mulai mengirimkan stiker bernada mengejek dan menyebarkan rumor tidak benar tentang seorang teman yang tidak ada di grup tersebut.',
                'choices' => [
                    [
                        'text' => 'Mengetik pesan asertif: "Teman-teman, yuk stop. Tidak baik membicarakan orang yang tidak ada di sini. Ayo kita bahas topik lain."',
                        'badge' => 'Keberanian Moral (Asertif) 🛡️',
                        'feedback' => 'Sangat hebat! Dibutuhkan keberanian besar untuk menghentikan perundungan verbal, dan tindakanmu menjadi rem bagi orang lain.'
                    ],
                    [
                        'text' => 'Hanya membaca (read) saja dan tidak ikut berkomentar agar tidak dianggap ikut-ikutan.',
                        'badge' => 'Penonton Pasif (*Bystander*) 😶',
                        'feedback' => 'Diam saat melihat ketidakadilan sering kali membuat pelaku merasa tindakannya dianggap wajar oleh orang lain.'
                    ],
                    [
                        'text' => 'Langsung memberi tahu teman yang digosipkan agar dia membalas teman-teman di grup.',
                        'badge' => 'Beresiko Memicu Konflik Lebih Besar ⚡',
                        'feedback' => 'Mendukung korban itu penting, namun mengadukan tanpa meredam suasana terlebih dahulu bisa memicu pertengkaran terbuka.'
                    ],
                ]
            ],
            [
                'id' => 3,
                'title' => 'Dilema Tugas Kelompok yang Menumpuk',
                'scenario' => 'Dalam satu kelompok tugas, ada seorang anggota yang sering tidak hadir dan tidak mengerjakan bagian tugasnya, padahal batas waktu pengumpulan tinggal 1 hari lagi.',
                'choices' => [
                    [
                        'text' => 'Mencari tahu penyebab ketidakhadirannya secara pribadi terlebih dahulu, lalu membagi tugas yang tersisa secara realistis dan melapor ke guru secara jujur dan objektif.',
                        'badge' => 'Kepemimpinan Bijak & Kerjasama 🤝',
                        'feedback' => 'Sikap dewasa! Menyelesaikan masalah dengan empati tanpa mengorbankan tanggung jawab akademis bersama.'
                    ],
                    [
                        'text' => 'Mengerjakan semua bagiannya sendiri, lalu mencoret namanya dari daftar anggota tanpa memberi tahu sebelumnya.',
                        'badge' => 'Kelelahan Sendiri & Reaktif 💢',
                        'feedback' => 'Kamu menyelesaikan tugas, tetapi kamu memikul beban sendirian dan menghilangkan kesempatan belajar bersama bagi temanmu.'
                    ],
                    [
                        'text' => 'Ikut pasrah dan tidak mengerjakan agar nilai satu kelompok jelek bersama.',
                        'badge' => 'Merugikan Diri Sendiri ❌',
                        'feedback' => 'Menyerah pada kelalaian orang lain hanya akan mengorbankan prestasimu sendiri.'
                    ],
                ]
            ],
        ];
    }
}
