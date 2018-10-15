<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Warranty;
use App\Observers\WarrantyObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boot Method
     *
     * @return void
     */
    public function boot()
    {
        Warranty::observe(WarrantyObserver::class);
    }
}
