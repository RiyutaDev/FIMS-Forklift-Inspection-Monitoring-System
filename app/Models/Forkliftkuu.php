<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Forklift extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table
     */
    protected $table = 'forklifts';

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
        | Relationship
        |--------------------------------------------------------------------------
        */
        'location_id',

        /*
        |--------------------------------------------------------------------------
        | Identity
        |--------------------------------------------------------------------------
        */
        'forklift_code',
        'asset_number',

        /*
        |--------------------------------------------------------------------------
        | Information
        |--------------------------------------------------------------------------
        */
        'brand',
        'model',
        'serial_number',
        'manufacture_year',
        'commissioning_date',

        /*
        |--------------------------------------------------------------------------
        | Capacity
        |--------------------------------------------------------------------------
        */
        'capacity',

        /*
        |--------------------------------------------------------------------------
        | Type
        |--------------------------------------------------------------------------
        */
        'fuel_type',

        /*
        |--------------------------------------------------------------------------
        | Vendor
        |--------------------------------------------------------------------------
        */
        'vendor_name',

        /*
        |--------------------------------------------------------------------------
        | QR
        |--------------------------------------------------------------------------
        */
        'qr_token',

        /*
        |--------------------------------------------------------------------------
        | Cache
        |--------------------------------------------------------------------------
        */
        'last_inspection_at',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        'is_active',

        /*
        |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        */
        'description',
    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'capacity'            => 'decimal:2',

        'manufacture_year'    => 'integer',

        'commissioning_date'  => 'date',

        'last_inspection_at'  => 'datetime',

        'is_active'           => 'boolean',

    ];

    /**
     * Boot Model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($forklift) {

            if (empty($forklift->qr_token)) {

                $forklift->qr_token = (string) Str::uuid();

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    /**
     * Forklift belongs to Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Forklift has many Inspections
     */
    public function inspections()
    {
        return $this->hasMany(
            Inspection::class,
            'forklift_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Active Forklifts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * By Location
     */
    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    /**
     * By Fuel Type
     */
    public function scopeFuelType($query, string $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return "{$this->forklift_code} - {$this->brand}";
    }

    public function getCapacityLabelAttribute()
    {
        return number_format($this->capacity, 2) . ' Ton';
    }

    public function getLocationNameAttribute()
    {
        return optional($this->location)->location_name;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function generateQrUrl(): string
    {
        return url('/inspection/' . $this->qr_token);
    }

    public function isElectric(): bool
    {
        return $this->fuel_type === 'Electric';
    }

    public function isDiesel(): bool
    {
        return $this->fuel_type === 'Diesel';
    }

    public function isLpg(): bool
    {
        return $this->fuel_type === 'LPG';
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}