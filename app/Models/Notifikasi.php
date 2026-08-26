<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'reference_id',
        'reference_type',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get visual icon, color scheme, category name, and action url for professional rendering
     */
    public function getMetaAttribute(): array
    {
        return match ($this->tipe) {
            'alpa_siswa' => [
                'icon'       => 'fa-solid fa-user-xmark',
                'gradient'   => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                'bg_light'   => '#fee2e2',
                'text_color' => '#dc2626',
                'badge'      => 'Presensi Siswa',
                'action_url' => url('/admin/absensi'),
            ],
            'jurnal_kosong' => [
                'icon'       => 'fa-solid fa-book-bookmark',
                'gradient'   => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                'bg_light'   => '#fef3c7',
                'text_color' => '#b45309',
                'badge'      => 'Jurnal Mengajar',
                'action_url' => url('/admin/absensi'),
            ],
            'izin_guru' => [
                'icon'       => 'fa-solid fa-file-signature',
                'gradient'   => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                'bg_light'   => '#eff6ff',
                'text_color' => '#2563eb',
                'badge'      => 'Izin Guru',
                'action_url' => url('/admin/absensi'),
            ],
            'dispensasi_siswa' => [
                'icon'       => 'fa-solid fa-certificate',
                'gradient'   => 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
                'bg_light'   => '#f5f3ff',
                'text_color' => '#7c3aed',
                'badge'      => 'Dispensasi',
                'action_url' => url('/admin/absensi'),
            ],
            default => [
                'icon'       => 'fa-solid fa-bell',
                'gradient'   => 'linear-gradient(135deg, #2b43b9 0%, #1e293b 100%)',
                'bg_light'   => '#eaeff8',
                'text_color' => '#2b43b9',
                'badge'      => 'Sistem',
                'action_url' => url('/admin/dashboard'),
            ],
        };
    }

    public function getTimeAgoAttribute(): string
    {
        if (!$this->created_at) return 'Baru saja';
        return $this->created_at->locale('id')->diffForHumans();
    }
}
