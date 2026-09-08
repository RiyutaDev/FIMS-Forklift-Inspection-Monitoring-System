<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'approvals';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignment
     */
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Relationships
        |--------------------------------------------------------------------------
        */

        'inspection_id',
        'approved_by',

        /*
        |--------------------------------------------------------------------------
        | Approval Information
        |--------------------------------------------------------------------------
        */

        'approval_status',
        'approval_note',
        'approved_at',

    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'approved_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Approval belongs to Inspection
     */
    public function inspection()
    {
        return $this->belongsTo(
            Inspection::class,
            'inspection_id'
        );
    }

    /**
     * Approval belongs to User (Supervisor/Admin)
     */
    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Approved Data
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'Approved');
    }

    /**
     * Rejected Data
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'Rejected');
    }

    /**
     * Approval by User
     */
    public function scopeByApprover($query, int $userId)
    {
        return $query->where('approved_by', $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Approver Name
     */
    public function getApproverNameAttribute()
    {
        return optional($this->approvedBy)->name;
    }

    /**
     * Inspection Number
     */
    public function getInspectionNumberAttribute()
    {
        return optional($this->inspection)->inspection_number;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check Approved Status
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'Approved';
    }

    /**
     * Check Rejected Status
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'Rejected';
    }

    /**
     * Check Approval Note
     */
    public function hasApprovalNote(): bool
    {
        return !empty($this->approval_note);
    }
}