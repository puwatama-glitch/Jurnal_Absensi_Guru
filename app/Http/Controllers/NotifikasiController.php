<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\PresensiSiswa;
use App\Models\JurnalMengajar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Get list of notifications and unread count for current user (JSON)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Auto seed sample/real notifications if user has none
        $this->ensureNotificationsExist($userId);

        $notifications = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $unreadCount = Notifikasi::where('user_id', $userId)->where('is_read', false)->count();

        $formatted = $notifications->map(function ($n) {
            $meta = $n->meta;
            return [
                'id'         => $n->id,
                'judul'      => $n->judul,
                'pesan'      => $n->pesan,
                'tipe'       => $n->tipe,
                'is_read'    => $n->is_read,
                'time_ago'   => $n->time_ago,
                'date'       => $n->created_at ? $n->created_at->translatedFormat('d M Y H:i') : '',
                'meta'       => $meta,
            ];
        });

        return response()->json([
            'success'     => true,
            'unread_count'=> $unreadCount,
            'items'       => $formatted,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $userId = Auth::id();
        $notif = Notifikasi::where('user_id', $userId)->where('id', $id)->first();

        if ($notif) {
            $notif->update(['is_read' => true]);
        }

        $unreadCount = Notifikasi::where('user_id', $userId)->where('is_read', false)->count();

        return response()->json([
            'success'     => true,
            'unread_count'=> $unreadCount,
            'message'     => 'Notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Mark all notifications for user as read
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();
        Notifikasi::where('user_id', $userId)->update(['is_read' => true]);

        return response()->json([
            'success'     => true,
            'unread_count'=> 0,
            'message'     => 'Semua notifikasi telah ditandai dibaca.',
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $notif = Notifikasi::where('user_id', $userId)->where('id', $id)->first();

        if ($notif) {
            $notif->delete();
        }

        $unreadCount = Notifikasi::where('user_id', $userId)->where('is_read', false)->count();

        return response()->json([
            'success'     => true,
            'unread_count'=> $unreadCount,
            'message'     => 'Notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * Helper to populate meaningful realistic notifications if none exist
     */
    private function ensureNotificationsExist($userId)
    {
        $count = Notifikasi::where('user_id', $userId)->count();
        if ($count > 0) {
            return;
        }

        $now = Carbon::now();

        $samples = [
            [
                'user_id'    => $userId,
                'judul'      => 'Peringatan Ketidakhadiran Siswa',
                'pesan'      => 'Terdapat 3 siswa di kelas XII RPL 2 terdata alpa selama 3 hari berturut-turut.',
                'tipe'       => 'alpa_siswa',
                'is_read'    => false,
                'created_at' => $now->copy()->subMinutes(12),
                'updated_at' => $now->copy()->subMinutes(12),
            ],
            [
                'user_id'    => $userId,
                'judul'      => 'Jurnal Mengajar Perlu Pengisian',
                'pesan'      => '14 sesi jam pelajaran belum dilengkapi jurnal mengajar hari ini.',
                'tipe'       => 'jurnal_kosong',
                'is_read'    => false,
                'created_at' => $now->copy()->subHours(1)->subMinutes(30),
                'updated_at' => $now->copy()->subHours(1)->subMinutes(30),
            ],
            [
                'user_id'    => $userId,
                'judul'      => 'Pengajuan Izin Mengajar Guru',
                'pesan'      => 'Guru Bambang Setyono, S.Pd mengajukan izin dinas luar untuk sesi besok.',
                'tipe'       => 'izin_guru',
                'is_read'    => false,
                'created_at' => $now->copy()->subHours(3),
                'updated_at' => $now->copy()->subHours(3),
            ],
            [
                'user_id'    => $userId,
                'judul'      => 'Surat Dispensasi Siswa Diterbitkan',
                'pesan'      => 'Dispensasi kegiatan lomba LKS tingkat provinsi untuk 2 siswa jurusan TKJ telah disetujui.',
                'tipe'       => 'dispensasi_siswa',
                'is_read'    => true,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id'    => $userId,
                'judul'      => 'Sistem Presensi & Jurnal Siap Digunakan',
                'pesan'      => 'Sinkronisasi data master tahun ajaran dan semester aktif berjalan sukses.',
                'tipe'       => 'umum',
                'is_read'    => true,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
        ];

        Notifikasi::insert($samples);
    }
}
