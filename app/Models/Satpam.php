<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satpam extends Model
{
    protected $table = 'satpam';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'pos_jaga',
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
