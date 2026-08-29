<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPiket extends Model
{
    protected $table = 'laporan_piket';

    protected $fillable = [
        'tanggal',
        'user_id',
        'guru_piket_id',
        'jam_mulai_piket',
        'jam_selesai_piket',
        'catatan_kejadian',
        'jumlah_guru_hadir',
        'jumlah_guru_izin',
        'jumlah_siswa_dispensasi',
        'jumlah_siswa_alpha',
        'status_piket',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function guruPiket()
    {
        return $this->belongsTo(GuruPiket::class, 'guru_piket_id');
    }
}
