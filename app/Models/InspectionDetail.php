<?php

namespace App\Models;

use App\Models\InspectionPhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class InspectionDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table Name
     */
    protected $table = 'inspection_details';

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
        'inspection_item_id',

        /*
        |--------------------------------------------------------------------------
        | Inspection Result
        |--------------------------------------------------------------------------
        */

        'result_status',
        'result_value',
        'result_text',

        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        'note',

        /*
        |--------------------------------------------------------------------------
        | Metadata
        |--------------------------------------------------------------------------
        */

        'is_passed',

    ];

    /**
     * Hidden Attributes
     */
    protected $hidden = [];

    /**
     * Attribute Casting
     */
    protected $casts = [

        'result_value' => 'decimal:2',

        'is_passed'    => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Detail belongs to Inspection
     */
    public function inspection()
    {
        return $this->belongsTo(
            Inspection::class,
            'inspection_id'
        );
    }

    /**
     * Detail belongs to Inspection Item
     */
    public function inspectionItem()
    {
        return $this->belongsTo(
            InspectionItem::class,
            'inspection_item_id'
        );
    }

        /**
     * Detail has many Inspection Photos
     */
    public function photos()
    {
        return $this->hasMany(
            InspectionPhoto::class,
            'inspection_detail_id'
        );
    }
    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Passed Items
     */
    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    /**
     * Failed Items
     */
    public function scopeFailed($query)
    {
        return $query->where('is_passed', false);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Inspection Item Name
     */
    public function getItemNameAttribute()
    {
        return optional($this->inspectionItem)->item_name;
    }

    /**
     * Display Result
     */
    public function getDisplayResultAttribute()
    {
        if (!is_null($this->result_status)) {
            return $this->result_status;
        }

        if (!is_null($this->result_value)) {
            return $this->result_value;
        }

        return $this->result_text;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check Passed Status
     */
    public function isPassed(): bool
    {
        return $this->is_passed === true;
    }

    /**
     * Check Failed Status
     */
    public function isFailed(): bool
    {
        return $this->is_passed === false;
    }

    /**
     * Check if Note Exists
     */
    public function hasNote(): bool
    {
        return !empty($this->note);
    }

    /**
     * Check if Result has Value
     */
    public function hasResult(): bool
    {
        return !is_null($this->result_status)
            || !is_null($this->result_value)
            || !is_null($this->result_text);
    }
}