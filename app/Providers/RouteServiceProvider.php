<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur ke "home" aplikasi kamu.
     *
     * Biasanya digunakan untuk redirect setelah login.
     */
    public const HOME = '/home';

    /**
     * Register layanan rute apa pun.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Rute API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Rute Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
