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
        // Create incident_reports table
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_id')->unique();
            $table->enum('incident_type', [
                'natural_disaster',
                'accident',
                'crime_security',
                'infrastructure',
                'health_emergency',
                'other'
            ]);
            $table->string('title');
            $table->longText('description');
            $table->string('location', 500);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->date('incident_date');
            $table->time('incident_time');
            $table->enum('urgency_level', ['low', 'medium', 'high']);
            $table->enum('status', [
                'pending',
                'under_review',
                'in_progress',
                'resolved',
                'cancelled',
                'overdue'
            ])->default('pending');
            $table->string('reporter_name', 100)->nullable();
            $table->string('reporter_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->json('assigned_to')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('response_time_minutes')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('report_id');
            $table->index('status');
            $table->index('incident_type');
            $table->index('urgency_level');
            $table->index('incident_date');
        });

        // Create incident_photos table
        Schema::create('incident_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_report_id');
            $table->string('photo_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('incident_report_id')
                ->references('id')
                ->on('incident_reports')
                ->onDelete('cascade');
            
            $table->index('incident_report_id');
        });

        // Create incident_status_histories table
        Schema::create('incident_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_report_id');
            $table->enum('old_status', [
                'pending',
                'under_review',
                'in_progress',
                'resolved',
                'cancelled',
                'overdue'
            ])->nullable();
            $table->enum('new_status', [
                'pending',
                'under_review',
                'in_progress',
                'resolved',
                'cancelled',
                'overdue'
            ]);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();
            
            $table->foreign('incident_report_id')
                ->references('id')
                ->on('incident_reports')
                ->onDelete('cascade');
            
            $table->foreign('changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            
            $table->index('incident_report_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_status_histories');
        Schema::dropIfExists('incident_photos');
        Schema::dropIfExists('incident_reports');
    }
};