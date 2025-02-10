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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('deskripsi')->nullable();
            $table->dateTime('tenggat_waktu');
            $table->enum('status', ['belum', 'selesai', 'terlambat'])->default('belum');
            $table->boolean('keterangan_skor')->default(false);
            $table->enum('prioritas', ['1', '2', '3']);
            $table->timestamps();
        });
        
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->string('name');
            $table->dateTime('tenggat_waktu');
            $table->enum('status', ['belum', 'selesai', 'terlambat'])->default('belum');
            $table->boolean('keterangan_skor')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
