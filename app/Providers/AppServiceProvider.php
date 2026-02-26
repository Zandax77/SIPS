<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
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
        // Create Super Admin automatically when database is empty
        $this->createSuperAdminIfNotExists();
    }

    /**
     * Create Super Admin if no petugas exists in database
     */
    private function createSuperAdminIfNotExists(): void
    {
        try {
            // Check if petugas table exists and is empty
            if (Schema::hasTable('petugas')) {
                $count = Petugas::count();
                
                if ($count == 0) {
                    Petugas::create([
                        'name' => 'Super Admin',
                        'email' => 'admin@sips.test',
                        'password' => Hash::make('admin123'),
                        'jabatan' => 'Super Admin',
                        'kelas' => null,
                        'role' => 'admin',
                        'status' => 'active',
                    ]);
                    
                    // Suppress output if not in console
                    if (!$this->app->runningInConsole()) {
                        session()->flash('success', 'Super Admin berhasil dibuat! Email: admin@sips.test | Password: admin123');
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not ready yet
            // This can happen during migration or first setup
        }
    }
}
