<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('roster_id')->constrained()->cascadeOnDelete(); // Changed from restrictOnDelete
            $table->date('date');
            $table->timestamp('check_in_at')->nullable();
            $table->string('status', 20); // present, late, absent, half_day, excused
            $table->string('marked_via', 20); // qr, manual_hod, auto_absent, leave_sync
            $table->foreignId('marked_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique('roster_id');
            $table->index(['user_id', 'date']);
            $table->index(['date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};