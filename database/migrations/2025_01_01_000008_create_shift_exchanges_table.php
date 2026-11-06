<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('target_id')->constrained('users')->restrictOnDelete();
           $table->foreignId('requester_roster_id')->constrained('rosters')->cascadeOnDelete(); // Changed
$table->foreignId('target_roster_id')->constrained('rosters')->cascadeOnDelete(); // Changed $table->text('reason');
            
            $table->string('peer_status', 20)->default('pending');
            $table->text('peer_note')->nullable();
            $table->timestamp('peer_responded_at')->nullable();
            
            $table->string('hod_status', 20)->default('pending');
            $table->text('hod_note')->nullable();
            $table->foreignId('hod_approved_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('hod_responded_at')->nullable();
            
            $table->string('status', 20)->default('peer_pending');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['requester_id', 'status']);
            $table->index(['target_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_exchanges');
    }
};