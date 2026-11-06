<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('type', 20); // performance, attendance
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedTinyInteger('score'); // 1-5
            $table->text('comment');
            $table->foreignId('given_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('edited_at')->nullable();
            $table->unsignedSmallInteger('edit_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'type', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};