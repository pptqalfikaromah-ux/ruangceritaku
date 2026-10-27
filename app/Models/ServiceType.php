<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
    ];

    public function scopeLayanan($query)
    {
        return $query->where('category', 'layanan');
    }

    public function scopePendukung($query)
    {
        return $query->where('category', 'pendukung');
    }
}
