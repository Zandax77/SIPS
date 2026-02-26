<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sekolah extends Model
{
    protected $table = 'sekolah';

    protected $fillable = [
        'nama_sekolah',
        'alamat_sekolah',
        'logo_sekolah',
    ];

    protected $appends = ['logo_url', 'logo_base64'];

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_sekolah) {
            return asset('storage/' . $this->logo_sekolah);
        }
        return null;
    }

    /**
     * Get the logo as base64 for PDF embedding
     */
    public function getLogoBase64Attribute(): ?string
    {
        if (!$this->logo_sekolah) {
            return null;
        }
        
        $path = storage_path('app/public/' . $this->logo_sekolah);
        
        if (!file_exists($path)) {
            return null;
        }
        
        $mimeType = mime_content_type($path);
        $data = file_get_contents($path);
        
        return 'data:' . $mimeType . ';base64,' . base64_encode($data);
    }

    /**
     * Get the first school record (singleton pattern)
     */
    public static function getSekolah(): ?Sekolah
    {
        return self::first();
    }

    /**
     * Get or create the first school record
     */
    public static function getOrCreate(): Sekolah
    {
        return self::first() ?? self::create([
            'nama_sekolah' => 'SMK Negeri',
            'alamat_sekolah' => '',
        ]);
    }

    /**
     * Upload and save logo
     */
    public function uploadLogo($file): bool
    {
        // Delete old logo if exists
        if ($this->logo_sekolah) {
            Storage::disk('public')->delete($this->logo_sekolah);
        }

        // Store new logo
        $path = $file->store('sekolah', 'public');
        
        return $this->update(['logo_sekolah' => $path]);
    }

    /**
     * Delete logo
     */
    public function deleteLogo(): bool
    {
        if ($this->logo_sekolah) {
            Storage::disk('public')->delete($this->logo_sekolah);
            return $this->update(['logo_sekolah' => null]);
        }
        return true;
    }
}

