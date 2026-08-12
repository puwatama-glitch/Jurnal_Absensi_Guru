<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalMengajar extends Model
{
    protected $table = 'jurnal_mengajar';

    protected $primaryKey = 'id_jurnal';

    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'id_kelas',
        'tanggal',
        'jam_ke',
        'materi',
        'jumlah_siswa_hadir',
        'jumlah_siswa_tidak_hadir',
        'status_guru',
        'catatan'
    ];
}