<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionItem extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | MODEL CONFIGURATION
    |--------------------------------------------------------------------------
    */

    /**
     * Table Name
     */
    protected $table = 'inspection_items';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | HIDDEN ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE CASTING
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'inspection_category_id' => 'integer',

        'is_critical'            => 'boolean',
        'requires_photo'        => 'boolean',
        'requires_note'         => 'boolean',
        'is_active'             => 'boolean',

        'sort_order'            => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Item belongs to Inspection Category.
     *
     * Penggunaan:
     *
     * $item->category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            InspectionCategory::class,
            'inspection_category_id',
            'id'
        );
    }

    /**
     * Alias relasi kategori.
     *
     * Method ini tidak mengubah relasi category() yang sudah digunakan.
     * Bisa digunakan jika pada bagian lain aplikasi memanggil:
     *
     * $item->inspectionCategory
     */
    public function inspectionCategory(): BelongsTo
    {
        return $this->belongsTo(
            InspectionCategory::class,
            'inspection_category_id',
            'id'
        );
    }

    /**
     * Item has many Inspection Details.
     *
     * Satu item checklist dapat memiliki banyak detail pemeriksaan
     * dari berbagai transaksi inspeksi.
     */
    public function inspectionDetails(): HasMany
    {
        return $this->hasMany(
            InspectionDetail::class,
            'inspection_item_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil item checklist yang aktif.
     *
     * Penggunaan:
     *
     * InspectionItem::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mengurutkan item checklist.
     */
    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('item_name', 'asc');
    }

    /**
     * Mengambil item yang bersifat kritis.
     */
    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    /**
     * Filter item berdasarkan jenis bahan bakar forklift.
     *
     * Item dengan applicable_fuel_type = All akan selalu ditampilkan.
     *
     * Contoh:
     *
     * InspectionItem::fuelType('Electric')->get();
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
     * Mengambil nama kategori item.
     *
     * Penggunaan:
     *
     * $item->category_name
     */
    public function getCategoryNameAttribute(): ?string
    {
        return optional($this->category)->category_name;
    }

    /**
     * Mengambil nama tampilan item.
     *
     * Contoh:
     *
     * FL-001 - Periksa kondisi rem
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->item_code} - {$this->item_name}";
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Memeriksa apakah item merupakan item kritis.
     */
    public function isCritical(): bool
    {
        return (bool) $this->is_critical;
    }

    /**
     * Memeriksa apakah foto wajib diunggah.
     */
    public function requiresPhoto(): bool
    {
        return (bool) $this->requires_photo;
    }

    /**
     * Memeriksa apakah catatan wajib diisi.
     */
    public function requiresNote(): bool
    {
        return (bool) $this->requires_note;
    }

    /**
     * Memeriksa apakah item sedang aktif.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}