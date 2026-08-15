<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    use HasFactory;

    protected $table = 'presensi_siswa';

    protected $fillable = [
        'id_jurnal',
        'id_siswa',
        'status',
        'keterangan',
    ];

    public function jurnal()
    {
        return $this->belongsTo(JurnalMengajar::class, 'id_jurnal', 'id_jurnal');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
