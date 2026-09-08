<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'inspection_categories';

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
        | Category Information
        |--------------------------------------------------------------------------
        */

        'category_code',
        'category_name',
        'description',

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
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Category has many Inspection Items
     */
    public function inspectionItems()
    {
        return $this->hasMany(
            InspectionItem::class,
            'category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Active Categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ordered Categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')
                     ->orderBy('category_name');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Total Inspection Items
     */
    public function getItemsCountAttribute(): int
    {
        return $this->inspectionItems()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Check Active Status
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}