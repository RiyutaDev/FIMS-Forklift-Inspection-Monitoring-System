<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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
        'employee_number',
        'name',
        'username',
        'email',
        'phone',
        'photo',
        'password',
        'location_id',
        'role_id',
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
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * User belongs to Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * User has many Inspections (Operator / Driver)
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
        return $this->hasMany(ActivityLog::class, 'user_id');
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

    public function scopeAdmin($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('role_name', ['Admin', 'Administrator']);
        });
    }

    public function scopeSupervisor($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('role_name', 'Supervisor');
        });
    }

    public function scopeOperator($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereIn('role_name', ['Operator', 'Driver']);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPhotoUrlAttribute()
    {
        if (empty($this->photo)) {
            return asset('images/default-user.png');
        }

        return Storage::disk('public')->url($this->photo);
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
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah User ber-role Admin / Administrator
     */
    public function isAdmin(): bool
    {
        $roleName = optional($this->role)->role_name;
        return in_array($roleName, ['Admin', 'Administrator']);
    }

    /**
     * Memeriksa apakah User ber-role Supervisor
     */
    public function isSupervisor(): bool
    {
        return optional($this->role)->role_name === 'Supervisor';
    }

    /**
     * Memeriksa apakah User ber-role Driver / Operator
     */
    public function isDriver(): bool
    {
        $roleName = optional($this->role)->role_name;
        return in_array($roleName, ['Driver', 'Operator']);
    }

    // Alias untuk kompatibilitas nama method
    public function isOperator(): bool
    {
        return $this->isDriver();
    }

    public function isAdministrator(): bool
    {
        return $this->isAdmin();
    }
}