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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Employee Information
            |--------------------------------------------------------------------------
            */

            $table->string('employee_number', 20)
                ->unique()
                ->comment('Employee ID / NIK');

            $table->string('name', 100);

            $table->string('username', 50)
                ->unique();

            $table->string('email')
                ->unique();

            $table->string('phone', 20)
                ->nullable();

            $table->string('photo')
                ->nullable()
                ->comment('Profile photo');

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            $table->string('password');

            $table->timestamp('email_verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Working Area
            |--------------------------------------------------------------------------
            */

            $table->foreignId('location_id')
                ->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | User Role
            |--------------------------------------------------------------------------
            */

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Account Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true)
                ->comment('Account status');

            /*
            |--------------------------------------------------------------------------
            | Login Activity
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_login_at')
                ->nullable();

            $table->rememberToken();

            $table->timestamps();

            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('username');
            $table->index('employee_number');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};