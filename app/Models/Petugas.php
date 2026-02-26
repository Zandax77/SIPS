<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
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
}
