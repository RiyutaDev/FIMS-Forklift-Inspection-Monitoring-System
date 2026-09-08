<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'inspection_items';

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
        'inspection_category_id',

        /*
        |--------------------------------------------------------------------------
        | Item Information
        |--------------------------------------------------------------------------
        */
        'item_code',
        'item_name',
        'description',

        /*
        |--------------------------------------------------------------------------
        | Applicable Forklift Type
        |--------------------------------------------------------------------------
        */
        'applicable_fuel_type',

        /*
        |--------------------------------------------------------------------------
        | Input Configuration
        |--------------------------------------------------------------------------
        */
        'input_type',
        'unit',

        /*
        |--------------------------------------------------------------------------
        | Inspection Rules
        |--------------------------------------------------------------------------
        */
        'is_critical',
        'requires_photo',
        'requires_note',

        /*
        |--------------------------------------------------------------------------
        | Display Configuration
        |--------------------------------------------------------------------------
        */
        'sort_order',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        'is_active',

    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'is_critical'    => 'boolean',
        'requires_photo' => 'boolean',
        'requires_note'  => 'boolean',
        'is_active'      => 'boolean',
        'sort_order'     => 'integer',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Item belongs to Inspection Category
     */
    public function category()
    {
        return $this->belongsTo(
            InspectionCategory::class,
            'inspection_category_id'
        );
    }

    /**
     * Item has many Inspection Details
     */
    public function inspectionDetails()
    {
        return $this->hasMany(
            InspectionDetail::class,
            'inspection_item_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Active Items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ordered Items
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')
                     ->orderBy('item_name');
    }

    /**
     * Critical Items
     */
    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    /**
     * Filter by Fuel Type
     */
    public function scopeFuelType($query, string $fuelType)
    {
        return $query->where(function ($q) use ($fuelType) {
            $q->where('applicable_fuel_type', 'All')
              ->orWhere('applicable_fuel_type', $fuelType);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Category Name
     */
    public function getCategoryNameAttribute()
    {
        return optional($this->category)->category_name;
    }

    /**
     * Display Name
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->item_code} - {$this->item_name}";
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    public function isCritical(): bool
    {
        return $this->is_critical;
    }

    public function requiresPhoto(): bool
    {
        return $this->requires_photo;
    }

    public function requiresNote(): bool
    {
        return $this->requires_note;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}