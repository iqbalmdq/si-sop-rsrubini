<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sop;
use App\Observers\SopObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Sop::observe(SopObserver::class);
    }
}