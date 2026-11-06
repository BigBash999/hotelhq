<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('shift_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->restrictOnDelete();
            $table->boolean('is_special_event')->default(false);
            $table->unsignedBigInteger('coverage_request_id')->nullable(); // Soft FK
            $table->timestamps();

            $table->unique(['user_id', 'date']);
            $table->index(['date', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rosters');
    }
};