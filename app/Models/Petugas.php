<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class Petugas extends Authenticatable
{
    use Notifiable;

    protected $table = 'petugas';

    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan',
        'kelas',
        'role',
        'status',
        'password_reset_token',
        'password_reset_expires',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'password_reset_expires' => 'datetime',
            'password_changed_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is OSIS
     */
    public function isOsis(): bool
    {
        return $this->role === 'osis';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is inactive (waiting for activation)
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Check if user is blocked
     */
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * Activate user account
     */
    public function activate(): bool
    {
        return $this->update(['status' => 'active']);
    }

    /**
     * Deactivate user account (set to inactive)
     */
    public function deactivate(): bool
    {
        return $this->update(['status' => 'inactive']);
    }

    /**
     * Block user account
     */
    public function block(): bool
    {
        return $this->update(['status' => 'blocked']);
    }

    /**
     * Generate password reset token
     */
    public function generatePasswordResetToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'password_reset_token' => $token,
            'password_reset_expires' => now()->addHours(24),
        ]);
        return $token;
    }

    /**
     * Validate password reset token
     */
    public function isValidResetToken(?string $token): bool
    {
        if (!$token || !$this->password_reset_token) {
            return false;
        }

        if ($this->password_reset_expires && now()->greaterThan($this->password_reset_expires)) {
            return false;
        }

        return $this->password_reset_token === $token;
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken(): bool
    {
        return $this->update([
            'password_reset_token' => null,
            'password_reset_expires' => null,
        ]);
    }

    /**
     * Reset password to a specific value
     */
    public function resetPassword(string $newPassword): bool
    {
        return $this->update([
            'password' => Hash::make($newPassword),
            'password_reset_token' => null,
            'password_reset_expires' => null,
            'password_changed_at' => now(),
        ]);
    }

    /**
     * Check if password needs to be changed (after reset by admin)
     */
    public function mustChangePassword(): bool
    {
        // Password should be changed if it was reset and hasn't been changed yet
        // For now, we'll use a simple check - if password_changed_at is null and there's no reset token
        // This means admin reset it
        return false; // Can be customized based on requirements
    }

    /**
     * Create default admin account if petugas table is empty
     * This is called automatically on application boot
     */
    public static function createDefaultAdmin(): ?self
    {
        // Check if there are any petugas records
        if (self::count() > 0) {
            return null;
        }

        // Create default admin
        return self::create([
            'name' => 'Super Admin',
            'email' => 'admin@sips.test',
            'password' => Hash::make('admin123'),
            'jabatan' => 'Super Admin',
            'kelas' => null,
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    /**
     * Check if admin exists in petugas table
     */
    public static function adminExists(): bool
    {
        return self::where('role', 'admin')->exists();
    }
}
