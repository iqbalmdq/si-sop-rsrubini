<?php

namespace App\Observers;

use App\Models\Notifikasi;
use App\Models\Sop;
use App\Models\SopHistory;
use Illuminate\Support\Facades\Auth;

class SopObserver
{
    public function created(Sop $sop): void
    {
        // Record history
        SopHistory::create([
            'sop_id' => $sop->id,
            'aksi' => 'created',
            'data_baru' => $sop->toArray(),
            'keterangan' => 'SOP baru dibuat',
            'user_id' => Auth::id(),
            'tanggal_aksi' => now(),
        ]);

        // Create notification
        $actor = $sop->creator->name ?? 'System';
        $this->createNotification(
            'SOP Baru Dibuat',
            "SOP baru '{$sop->judul_sop}' ({$sop->nomor_sop}) telah dibuat oleh {$actor}",
            'sop_baru',
            $sop->id,
            $sop->bidang_bagian
        );
    }

    public function updating(Sop $sop): void
    {
        // Store original data before update - THIS IS THE PROBLEM
        // $sop->original_data = $sop->getOriginal(); // Remove this line
    }

    public function updated(Sop $sop): void
    {
        // Use getOriginal() directly instead of storing in original_data
        $originalData = $sop->getOriginal();
        $newData = $sop->toArray();

        // Check if status changed
        $statusChanged = isset($originalData['status']) && $originalData['status'] !== $sop->status;

        $aksi = $statusChanged ? 'status_changed' : 'updated';
        $keterangan = $statusChanged
            ? "Status SOP diubah dari '{$originalData['status']}' ke '{$sop->status}'"
            : 'SOP diperbarui';

        // Record history
        SopHistory::create([
            'sop_id' => $sop->id,
            'aksi' => $aksi,
            'data_lama' => $originalData,
            'data_baru' => $newData,
            'keterangan' => $keterangan,
            'user_id' => Auth::id(),
            'tanggal_aksi' => now(),
        ]);

        // Create notification
        $notifType = $statusChanged ? 'sop_update' : 'sop_update';
        $actor = optional(Auth::user())->name ?? ($sop->creator->name ?? 'System');
        $this->createNotification(
            $statusChanged ? 'Status SOP Diubah' : 'SOP Diperbarui',
            "SOP '{$sop->judul_sop}' ({$sop->nomor_sop}) telah diperbarui oleh {$actor}",
            $notifType,
            $sop->id,
            $sop->bidang_bagian
        );
    }

    public function deleting(Sop $sop): void
    {
        // Record history
        SopHistory::create([
            'sop_id' => $sop->id,
            'aksi' => 'deleted',
            'data_lama' => $sop->toArray(),
            'keterangan' => 'SOP dihapus',
            'user_id' => Auth::id(),
            'tanggal_aksi' => now(),
        ]);

        // Create notification
        $actor = optional(Auth::user())->name ?? ($sop->creator->name ?? 'System');
        $this->createNotification(
            'SOP Dihapus',
            "SOP '{$sop->judul_sop}' ({$sop->nomor_sop}) telah dihapus oleh {$actor}",
            'sop_delete',
            $sop->id,
            $sop->bidang_bagian
        );
    }

    private function createNotification(string $judul, string $pesan, string $tipe, int $sopId, ?string $targetBidang): void
    {
        Notifikasi::create([
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'sop_id' => $sopId,
            'target_bidang' => $targetBidang,
            'created_by' => Auth::id(),
        ]);
    }
}
