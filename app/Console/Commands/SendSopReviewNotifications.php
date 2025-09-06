<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sop;
use App\Models\Notifikasi;
use Carbon\Carbon;

class SendSopReviewNotifications extends Command
{
    protected $signature = 'sop:send-review-notifications';
    protected $description = 'Send notifications for SOPs that need review';

    public function handle()
    {
        // SOP yang perlu review (lebih dari 1 tahun)
        $sopsNeedReview = Sop::where('status', 'aktif')
            ->where('tanggal_berlaku', '<', Carbon::now()->subYear())
            ->whereDoesntHave('notifikasis', function ($query) {
                $query->where('tipe', 'sop_review')
                      ->where('created_at', '>', Carbon::now()->subDays(30)); // Jangan kirim notif jika sudah dikirim dalam 30 hari terakhir
            })
            ->with(['creator', 'kategori'])
            ->get();

        foreach ($sopsNeedReview as $sop) {
            Notifikasi::create([
                'judul' => 'SOP Perlu Review',
                'pesan' => "SOP '{$sop->judul_sop}' ({$sop->nomor_sop}) sudah lebih dari 1 tahun dan perlu direview.",
                'tipe' => 'sop_review',
                'sop_id' => $sop->id,
                'target_bidang' => $sop->bidang_bagian,
                'created_by' => 1, // System user
            ]);
        }

        $this->info("Sent review notifications for {$sopsNeedReview->count()} SOPs.");
        
        return Command::SUCCESS;
    }
}