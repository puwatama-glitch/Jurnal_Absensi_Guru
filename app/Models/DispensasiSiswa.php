<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispensasiSiswa extends Model
{
    protected $table = 'dispensasi_siswa';

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'jam_keluar',
        'jam_kembali',
        'alasan',
        'bukti_file',
        'status',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'diinput_oleh',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function diinputOlehUser()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    public function disetujuiOlehUser()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
