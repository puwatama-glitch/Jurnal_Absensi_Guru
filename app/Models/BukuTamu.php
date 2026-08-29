<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    protected $table = 'buku_tamu';

    protected $fillable = [
        'tanggal',
        'nama_tamu',
        'instansi',
        'no_hp',
        'keperluan',
        'bertemu_dengan',
        'no_kendaraan',
        'jam_masuk',
        'jam_keluar',
        'status',
        'catatan_satpam',
        'satpam_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function satpamUser()
    {
        return $this->belongsTo(User::class, 'satpam_id');
    }
}
