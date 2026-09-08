<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_details', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('inspection_item_id')
                ->constrained('inspection_items')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Inspection Result
            |--------------------------------------------------------------------------
            */

            // Untuk OK_NG / YES_NO
            $table->string('result_status',20)->nullable();

            // Untuk NUMBER / DECIMAL / PERCENTAGE
            $table->decimal('result_value',10,2)->nullable();

            // Untuk TEXT
            $table->text('result_text')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('note')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_passed')->nullable();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Business Rules
            |--------------------------------------------------------------------------
            */

            $table->unique(
                [
                    'inspection_id',
                    'inspection_item_id'
                ],
                'uk_inspection_item'
            );

            $table->index('inspection_id');
            $table->index('inspection_item_id');
            $table->index('is_passed');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_details');
    }
};