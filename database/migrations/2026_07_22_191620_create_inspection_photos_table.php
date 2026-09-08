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
        Schema::create('inspection_photos', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('inspection_detail_id')
                ->constrained('inspection_details')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Photo Information
            |--------------------------------------------------------------------------
            */

            $table->string('photo_name',255);

            $table->string('photo_path',500);

            $table->string('mime_type',100)->nullable();

            $table->unsignedBigInteger('photo_size')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */

            $table->string('caption',255)->nullable();

            $table->timestamp('taken_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Upload Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('inspection_detail_id');
            $table->index('uploaded_by');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_photos');
    }
};