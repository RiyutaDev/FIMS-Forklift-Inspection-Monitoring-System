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
        Schema::create('approvals', function (Blueprint $table) {

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

            $table->foreignId('approved_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Approval Information
            |--------------------------------------------------------------------------
            */

            $table->enum('approval_status', [
                'Approved',
                'Rejected',
            ]);

            $table->text('approval_note')->nullable();

            $table->timestamp('approved_at');

            /*
            |--------------------------------------------------------------------------
            | Business Rule
            |--------------------------------------------------------------------------
            */

            $table->unique('inspection_id', 'uk_inspection_approval');

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('approved_by');
            $table->index('approval_status');
            $table->index('approved_at');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};