<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'users';

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
        | Employee Information
        |--------------------------------------------------------------------------
        */
        'employee_number',
        'name',
        'username',
        'email',
        'phone',
        'photo',

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */
        'password',

        /*
        |--------------------------------------------------------------------------
        | Relationship
        |--------------------------------------------------------------------------
        */
        'location_id',
        'role_id',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        'is_active',
        'last_login_at',
        'email_verified_at',

    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute Casting
     */
    protected $casts = [
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'last_login_at'     => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * User belongs to Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * User belongs to Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * User has many Inspections (Operator)
     */
    public function inspections()
    {
        return $this->hasMany(
            Inspection::class,
            'operator_id'
        );
    }

    /**
     * User uploads many Inspection Photos
     */
    public function inspectionPhotos()
    {
        return $this->hasMany(
            InspectionPhoto::class,
            'uploaded_by'
        );
    }

    /**
     * User approves many Inspections
     */
    public function approvals()
    {
        return $this->hasMany(
            Approval::class,
            'approved_by'
        );
    }

    /**
     * User has many Activity Logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOperator($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('role_name', 'Operator');
        });
    }

    public function scopeSupervisor($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('role_name', 'Supervisor');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-user.png');
    }

    public function getRoleNameAttribute()
    {
        return $this->role?->role_name;
    }

    public function getLocationNameAttribute()
    {
        return $this->location?->location_name;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function isAdministrator(): bool
    {
        return optional($this->role)->role_name === 'Administrator';
    }

    public function isSupervisor(): bool
    {
        return optional($this->role)->role_name === 'Supervisor';
    }

    public function isOperator(): bool
    {
        return optional($this->role)->role_name === 'Operator';
    }
}