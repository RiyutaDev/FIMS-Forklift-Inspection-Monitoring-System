<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class InspectionPhoto extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'inspection_photos';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'inspection_detail_id',
        'uploaded_by',
        'photo_name',
        'photo_path',
        'mime_type',
        'photo_size',
        'caption',
        'taken_at',
    ];

    /**
     * Attribute Casting
     */
    protected $casts = [
        'photo_size' => 'integer',
        'taken_at'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Photo belongs to Inspection Detail
     */
    public function inspectionDetail()
    {
        return $this->belongsTo(
            InspectionDetail::class,
            'inspection_detail_id'
        );
    }

    /**
     * Uploaded By User
     */
    public function uploadedBy()
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeUploadedBy($query, int $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Photo URL menggunakan Laravel Storage Facade
     */
    public function getPhotoUrlAttribute()
    {
        if (empty($this->photo_path)) {
            return asset('images/placeholder-inspection.png'); // Default placeholder
        }

        return Storage::disk('public')->url($this->photo_path);
    }

    /**
     * Photo Size (KB)
     */
    public function getPhotoSizeKbAttribute()
    {
        if (is_null($this->photo_size)) {
            return null;
        }

        return round($this->photo_size / 1024, 2);
    }

    /**
     * Photo Size (MB)
     */
    public function getPhotoSizeMbAttribute()
    {
        if (is_null($this->photo_size)) {
            return null;
        }

        return round($this->photo_size / 1048576, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    public function hasCaption(): bool
    {
        return !empty($this->caption);
    }

    public function isUploaded(): bool
    {
        return !empty($this->photo_path) && Storage::disk('public')->exists($this->photo_path);
    }
}