<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Petugas;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-create default admin account if petugas table is empty
        $this->createDefaultAdminIfNeeded();
    }

    /**
     * Create default admin account if no petugas exists
     */
    private function createDefaultAdminIfNeeded(): void
    {
        try {
            // Only create default admin if table exists and is empty
            if (\Illuminate\Support\Facades\Schema::hasTable('petugas')) {
                Petugas::createDefaultAdmin();
            }
        } catch (\Exception $e) {
            // Silently fail - table might not exist during installation
            // The admin can be created manually via seeder or after migration
        }
    }
}
