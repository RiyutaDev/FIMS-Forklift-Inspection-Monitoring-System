<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::create('inspection_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Category Relationship
            |--------------------------------------------------------------------------
            */
            $table->foreignId('inspection_category_id')
                ->constrained('inspection_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Item Information
            |--------------------------------------------------------------------------
            */
            $table->string('item_code', 20)->unique();

            $table->string('item_name', 100);

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Applicable Forklift Type
            |--------------------------------------------------------------------------
            */
            $table->enum('applicable_fuel_type', [
                'All',
                'Electric',
                'Diesel',
                'LPG'
            ])->default('All');

            /*
            |--------------------------------------------------------------------------
            | Input Configuration
            |--------------------------------------------------------------------------
            */
            $table->enum('input_type', [
                'OK_NG',
                'YES_NO',
                'NUMBER',
                'DECIMAL',
                'PERCENTAGE',
                'TEXT'
            ])->default('OK_NG');

            $table->string('unit', 20)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Inspection Rules
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_critical')
                ->default(false)
                ->comment('NG or abnormal value may cause forklift to be declared not ready');

            $table->boolean('requires_photo')->default(false);

            $table->boolean('requires_note')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Display Configuration
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('sort_order')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};
