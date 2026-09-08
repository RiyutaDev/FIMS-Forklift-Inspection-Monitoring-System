<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'inspections';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'inspection_number',
        'forklift_id',
        'operator_id',
        'inspection_date',
        'inspection_shift',
        'inspection_started_at',
        'inspection_completed_at',
        'overall_result',
        'status',
        'remarks',
        'submitted_by',
        'submitted_at',
    ];

    /**
     * Attribute Casting
     */
    protected $casts = [
        'inspection_date'         => 'date',
        'inspection_started_at'   => 'datetime',
        'inspection_completed_at' => 'datetime',
        'submitted_at'            => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Forklift
     */
    public function forklift()
    {
        return $this->belongsTo(
            Forklift::class,
            'forklift_id'
        );
    }

    /**
     * Operator (Driver)
     */
    public function operator()
    {
        return $this->belongsTo(
            User::class,
            'operator_id'
        );
    }

    /**
     * Submitted By
     */
    public function submittedBy()
    {
        return $this->belongsTo(
            User::class,
            'submitted_by'
        );
    }

    /**
     * Inspection Details
     */
    public function details()
    {
        return $this->hasMany(
            InspectionDetail::class,
            'inspection_id'
        );
    }

    /**
     * Inspection Photos (Has Many Through InspectionDetail)
     */
    public function photos()
    {
        return $this->hasManyThrough(
            InspectionPhoto::class,
            InspectionDetail::class,
            'inspection_id',        // Foreign key on inspection_details table
            'inspection_detail_id', // Foreign key on inspection_photos table
            'id',                   // Local key on inspections table
            'id'                    // Local key on inspection_details table
        );
    }

    /**
     * Approval
     */
    public function approval()
    {
        return $this->hasOne(
            Approval::class,
            'inspection_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'Submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeInspectionDate($query, $date)
    {
        return $query->whereDate('inspection_date', $date);
    }

    public function scopeShift($query, string $shift)
    {
        return $query->where('inspection_shift', $shift);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getOperatorNameAttribute()
    {
        return optional($this->operator)->name;
    }

    public function getForkliftCodeAttribute()
    {
        return optional($this->forklift)->forklift_code;
    }

    public function getLocationNameAttribute()
    {
        return optional($this->forklift?->location)->location_name;
    }

    public function getDurationAttribute()
    {
        if (!$this->inspection_started_at || !$this->inspection_completed_at) {
            return null;
        }

        return $this->inspection_started_at->diffForHumans(
            $this->inspection_completed_at,
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'Submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    public function isReady(): bool
    {
        return $this->overall_result === 'Ready';
    }

    public function isNotReady(): bool
    {
        return $this->overall_result === 'Not Ready';
    }
}