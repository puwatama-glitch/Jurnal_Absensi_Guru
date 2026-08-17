<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinGuru extends Model
{
    protected $table = 'izin_guru';

    protected $fillable = [
        'id_guru',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_izin',
        'alasan',
        'bukti_file',
        'status',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'catatan_persetujuan',
        'diinput_oleh',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
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
