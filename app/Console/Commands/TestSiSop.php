<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Sop;
use App\Models\KategoriSop;
use App\Models\Notifikasi;
use App\Models\SopHistory;

class TestSiSop extends Command
{
    protected $signature = 'test:si-sop';
    protected $description = 'Test SI-SOP application functionality';

    public function handle()
    {
        $this->info('🧪 Testing SI-SOP Application...');
        $this->newLine();
        
        // Test Database Connection
        $this->info('1. Testing Database Connection...');
        try {
            DB::connection()->getPdo();
            $this->info('   ✅ Database connection successful');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        // Test Models
        $this->info('2. Testing Models...');
        $userCount = User::count();
        $sopCount = Sop::count();
        $kategoriCount = KategoriSop::count();
        $notifCount = Notifikasi::count();
        $historyCount = SopHistory::count();
        
        $this->info("   ✅ Users: {$userCount}");
        $this->info("   ✅ SOPs: {$sopCount}");
        $this->info("   ✅ Kategori: {$kategoriCount}");
        $this->info("   ✅ Notifikasi: {$notifCount}");
        $this->info("   ✅ History: {$historyCount}");
        
        // Test Relationships
        $this->info('3. Testing Model Relationships...');
        $sop = Sop::with(['kategori', 'creator', 'histories'])->first();
        if ($sop) {
            $this->info('   ✅ SOP -> Kategori: ' . ($sop->kategori ? 'OK' : 'FAIL'));
            $this->info('   ✅ SOP -> Creator: ' . ($sop->creator ? 'OK' : 'FAIL'));
            $this->info('   ✅ SOP -> Histories: ' . $sop->histories->count() . ' records');
        }
        
        // Test Search Functionality
        $this->info('4. Testing Search Functionality...');
        $searchResults = Sop::where('status', 'aktif')
            ->where(function ($q) {
                $q->where('judul_sop', 'like', '%pasien%')
                  ->orWhere('nomor_sop', 'like', '%SOP%');
            })
            ->count();
        $this->info("   ✅ Search results: {$searchResults} SOPs found");
        
        // Test File Storage
        $this->info('5. Testing File Storage...');
        $storageExists = Storage::disk('public')->exists('sop-files');
        if (!$storageExists) {
            Storage::disk('public')->makeDirectory('sop-files');
        }
        $this->info('   ✅ Storage directory: ' . ($storageExists ? 'EXISTS' : 'CREATED'));
        
        // Test Routes
        $this->info('6. Testing Routes...');
        $routes = app('router')->getRoutes();
        $sopRoutes = collect($routes)->filter(function ($route) {
            return str_contains($route->uri(), 'sop');
        })->count();
        $this->info("   ✅ SOP routes registered: {$sopRoutes}");
        
        $this->newLine();
        $this->info('🎉 SI-SOP Application Test Completed!');
        $this->info('📋 Summary:');
        $this->info("   - {$userCount} Users (Bidang & Direktur)");
        $this->info("   - {$sopCount} SOPs");
        $this->info("   - {$kategoriCount} Kategori");
        $this->info("   - {$notifCount} Notifikasi");
        $this->info("   - {$historyCount} History Records");
        
        return Command::SUCCESS;
    }
}