<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coverage_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requesting_hod_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('from_department_id')->constrained('departments')->restrictOnDelete();
            $table->foreignId('to_department_id')->constrained('departments')->restrictOnDelete();
            $table->date('needed_date');
            $table->foreignId('shift_id')->constrained()->restrictOnDelete();
            $table->text('reason');
            $table->string('status', 20)->default('pending');
            $table->foreignId('responding_hod_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->text('response_note')->nullable();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['to_department_id', 'status']);
            $table->index(['requesting_hod_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coverage_requests');
    }
};