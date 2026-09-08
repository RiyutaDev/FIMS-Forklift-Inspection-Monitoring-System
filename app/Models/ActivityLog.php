<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Table Name
     */
    protected $table = 'activity_logs';

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
        | User
        |--------------------------------------------------------------------------
        */

        'user_id',

        /*
        |--------------------------------------------------------------------------
        | Module
        |--------------------------------------------------------------------------
        */

        'module',

        /*
        |--------------------------------------------------------------------------
        | Action
        |--------------------------------------------------------------------------
        */

        'action',

        /*
        |--------------------------------------------------------------------------
        | Polymorphic Relation
        |--------------------------------------------------------------------------
        */

        'subject_type',
        'subject_id',

        /*
        |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        */

        'description',

        /*
        |--------------------------------------------------------------------------
        | Additional Data
        |--------------------------------------------------------------------------
        */

        'properties',

        /*
        |--------------------------------------------------------------------------
        | Request Information
        |--------------------------------------------------------------------------
        */

        'ip_address',
        'user_agent',

        /*
        |--------------------------------------------------------------------------
        | Batch Process
        |--------------------------------------------------------------------------
        */

        'batch_uuid',

    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'properties' => 'array',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Activity belongs to User
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Polymorphic Subject
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Filter by Module
     */
    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Filter by Action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Filter by User
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filter by Batch UUID
     */
    public function scopeBatch($query, string $batchUuid)
    {
        return $query->where('batch_uuid', $batchUuid);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * User Name
     */
    public function getUserNameAttribute()
    {
        return optional($this->user)->name;
    }

    /**
     * Subject Model
     */
    public function getSubjectModelAttribute()
    {
        return class_basename($this->subject_type);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check Login Activity
     */
    public function isLogin(): bool
    {
        return $this->action === 'Login';
    }

    /**
     * Check Logout Activity
     */
    public function isLogout(): bool
    {
        return $this->action === 'Logout';
    }

    /**
     * Check Create Activity
     */
    public function isCreate(): bool
    {
        return $this->action === 'Create';
    }

    /**
     * Check Update Activity
     */
    public function isUpdate(): bool
    {
        return $this->action === 'Update';
    }

    /**
     * Check Delete Activity
     */
    public function isDelete(): bool
    {
        return $this->action === 'Delete';
    }

    /**
     * Check Submit Activity
     */
    public function isSubmit(): bool
    {
        return $this->action === 'Submit';
    }

    /**
     * Check Approve Activity
     */
    public function isApprove(): bool
    {
        return $this->action === 'Approve';
    }

    /**
     * Check Reject Activity
     */
    public function isReject(): bool
    {
        return $this->action === 'Reject';
    }

    /**
     * Check Restore Activity
     */
    public function isRestore(): bool
    {
        return $this->action === 'Restore';
    }

    /**
     * Check Export Activity
     */
    public function isExport(): bool
    {
        return $this->action === 'Export';
    }
}