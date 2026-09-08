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
        Schema::create('inspections', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Inspection Information
            |--------------------------------------------------------------------------
            */
            $table->string('inspection_number', 30)->unique();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */
            $table->foreignId('forklift_id')
                ->constrained('forklifts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('operator_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Inspection Schedule
            |--------------------------------------------------------------------------
            */
            $table->date('inspection_date');

            $table->enum('inspection_shift', [
                'Shift 1',
                'Shift 2',
                'Shift 3',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Inspection Timeline
            |--------------------------------------------------------------------------
            */
            $table->timestamp('inspection_started_at')->nullable();

            $table->timestamp('inspection_completed_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Inspection Result
            |--------------------------------------------------------------------------
            */
            $table->enum('overall_result', [
                'Ready',
                'Not Ready',
            ])->nullable();

            /*
            |--------------------------------------------------------------------------
            | Workflow Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'Draft',
                'Submitted',
                'Approved',
                'Rejected',
            ])->default('Draft');

            /*
            |--------------------------------------------------------------------------
            | General Remarks
            |--------------------------------------------------------------------------
            */
            $table->text('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Submission Information
            |--------------------------------------------------------------------------
            */
            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('submitted_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Business Rules
            |--------------------------------------------------------------------------
            | One forklift can only have one inspection
            | per date and shift.
            |--------------------------------------------------------------------------
            */
            $table->unique(
                [
                    'forklift_id',
                    'inspection_date',
                    'inspection_shift'
                ],
                'uk_forklift_inspection'
            );

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('inspection_date');
            $table->index('status');
            $table->index('forklift_id');
            $table->index('operator_id');

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};