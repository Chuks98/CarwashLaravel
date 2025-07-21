<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;  // ✅ Add this
use App\Console\Kernel as AppConsoleKernel;                       // ✅ And this

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ✅ Force Laravel to use your custom Kernel
        $this->app->singleton(ConsoleKernelContract::class, AppConsoleKernel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
