<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relasi utama:
     * Satu kategori memiliki banyak item checklist.
     */
    public function inspectionItems(): HasMany
    {
        return $this->hasMany(
            InspectionItem::class,
            'inspection_category_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil kategori yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mengurutkan kategori berdasarkan sort_order,
     * kemudian berdasarkan nama kategori.
     */
    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('category_name', 'asc');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung jumlah item checklist dalam kategori.
     *
     * Penggunaan:
     *
     * $category->items_count
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
     * Memeriksa apakah kategori sedang aktif.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}