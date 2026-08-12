<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalMengajar extends Model
{
    protected $table = 'jurnal_mengajar';

    protected $primaryKey = 'id_jurnal';

    protected $fillable = [
        'id_jadwal',
        'id_guru',
        'id_kelas',
        'id_mapel',
        'tanggal',
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'materi',
        'jumlah_siswa_hadir',
        'jumlah_siswa_tidak_hadir',
        'status_guru',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'id_jadwal', 'id_jadwal');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function presensiSiswa()
    {
        return $this->hasMany(PresensiSiswa::class, 'id_jurnal', 'id_jurnal');
    }
}