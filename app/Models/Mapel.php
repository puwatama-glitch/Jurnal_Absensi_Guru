<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;

    protected $table = 'mapel';

    protected $primaryKey = 'id_mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok',
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_mapel', 'id_mapel');
    }

    public function jurnalMengajar()
    {
        return $this->hasMany(JurnalMengajar::class, 'id_mapel', 'id_mapel');
    }
}
