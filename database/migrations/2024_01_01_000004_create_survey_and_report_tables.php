<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Surveys table
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('survey_number')->unique();
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('status')->default('pending');
            
            // Survey details
            $table->string('survey_type');
            $table->text('description')->nullable();
            $table->date('survey_date');
            $table->json('survey_data')->nullable();
            
            // Location
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Results
            $table->boolean('requires_action')->default(false);
            $table->text('recommendations')->nullable();
            $table->json('findings')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Survey files table
        Schema::create('survey_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('file_category');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Survey history table
        Schema::create('survey_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('action');
            $table->string('status_from')->nullable();
            $table->string('status_to')->nullable();
            $table->text('notes')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();
        });

        // Reports table
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->string('report_type');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->string('status')->default('draft');
            
            // Report details
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('report_data')->nullable();
            $table->date('report_date');
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            
            // References
            $table->foreignId('license_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('survey_id')->nullable()->constrained()->onDelete('restrict');
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Report files table
        Schema::create('report_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('file_category');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_files');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('survey_history');
        Schema::dropIfExists('survey_files');
        Schema::dropIfExists('surveys');
    }
}; 