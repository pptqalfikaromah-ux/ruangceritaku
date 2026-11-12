<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinnerParticipant extends Model
{
    use HasFactory;

    protected $table = 'spinner_participants';

    protected $fillable = [
        'name',
        'is_active',
        'win_count',
        'last_won_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'win_count' => 'integer',
        'last_won_at' => 'datetime',
    ];

    /**
     * Daftar nama bawaan (48 nama)
     */
    public static function defaultNames(): array
    {
        return [
            'Anis Labib',
            'Arwani Nafa',
            'Esianus Wasini',
            'Faizal Andrew Jatmiko',
            'Ahmad Ainul Khirom',
            'Saiful Anwar',
            'Ahmad Yusuf',
            'Aly Asrofi',
            'Isti Arini',
            'M. Adibul Muafa',
            'Jannah Fatikhatul',
            'Fuad Maimun',
            'Istichomah Yuanita',
            'Puji Prastyowati',
            'Komarudin',
            'faizatul khotimah',
            'Supeno',
            'Syarif Ahdiyat',
            'Khikmatul Khafidah',
            'Muslih Nurwanto',
            'Mila Marlina',
            'Dewi Istiyani',
            'Muhammad Ashabul Yamin',
            'Muhammad Atho\'illah',
            'Sholikul Ridwan',
            'putri luhur handayani',
            'Muhammad Muhlisin',
            'Riza Pramudita',
            'Salma Karima',
            'Siti Masruroh',
            'Sumiatun',
            'uswah hasanah',
            'Tsaqibul Fikri',
            'Lastari Anwar',
            'Tony Dwi Saputra',
            'Gigih Rama',
            'Dora Suryarita',
            'Hayyin Anjasmoro',
            'Nafisa Tunnisa',
            'Nailatul Iza',
            'Khoirur Rozaq',
            'Wiwin Widjianto',
            'Ulir Riya Kustiana',
            'Faizal Rizqi',
            'Fauzul Muna',
            'Jelita Tiara',
            'Ridho Syahbibi',
            'Amelia Cantik',
        ];
    }
}

