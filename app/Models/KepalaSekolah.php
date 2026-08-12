<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaSekolah extends Model
{
    protected $table = 'kepala_sekolah';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'periode_jabatan',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
