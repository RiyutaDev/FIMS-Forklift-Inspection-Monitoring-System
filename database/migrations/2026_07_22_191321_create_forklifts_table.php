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
        Schema::create('forklifts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Location Relationship
            |--------------------------------------------------------------------------
            */
            $table->foreignId('location_id')
                ->constrained('locations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Forklift Identity
            |--------------------------------------------------------------------------
            */
            $table->string('forklift_code', 30)->unique();
            $table->string('asset_number', 50)->unique();

            /*
            |--------------------------------------------------------------------------
            | Forklift Information
            |--------------------------------------------------------------------------
            */
            $table->string('brand', 100);

            $table->string('model', 100)->nullable();

            $table->string('serial_number', 100)->nullable();

            $table->year('manufacture_year')->nullable();

            $table->date('commissioning_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            | Capacity is stored in Ton.
            | Example:
            | 2.50 = 2.5 Ton
            |--------------------------------------------------------------------------
            */
            $table->decimal('capacity', 5, 2);

            /*
            |--------------------------------------------------------------------------
            | Forklift Type
            |--------------------------------------------------------------------------
            */
            $table->enum('fuel_type', [
                'Electric',
                'Diesel',
                'LPG'
            ])->default('Electric');

            /*
            |--------------------------------------------------------------------------
            | Vendor Information
            |--------------------------------------------------------------------------
            */
            $table->string('vendor_name', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | QR Token
            |--------------------------------------------------------------------------
            | Used to generate QR Code dynamically.
            | Example:
            | https://fims.company.com/inspection/{qr_token}
            |--------------------------------------------------------------------------
            */
            $table->uuid('qr_token')->unique();

            /*
            |--------------------------------------------------------------------------
            | Cache Information
            |--------------------------------------------------------------------------
            | Last inspection timestamp for dashboard optimization.
            | Inspection results remain in inspections table.
            |--------------------------------------------------------------------------
            */
            $table->timestamp('last_inspection_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Master Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Additional Information
            |--------------------------------------------------------------------------
            */
            $table->text('description')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forklifts');
    }
};
