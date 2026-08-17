<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guru extends Model
{
    use SoftDeletes;

    protected $table = 'guru';

    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_guru', 'id_guru');
    }

    public function jurnalMengajar()
    {
        return $this->hasMany(JurnalMengajar::class, 'id_guru', 'id_guru');
    }

    public function izinGuru()
    {
        return $this->hasMany(IzinGuru::class, 'id_guru', 'id_guru');
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id', 'id_guru');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }
}