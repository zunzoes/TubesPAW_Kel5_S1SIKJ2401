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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            // User yang memulai percakapan (Customer)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Admin yang menangani chat ini (bisa kosong jika belum ada admin yang handle)
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Status untuk mengontrol apakah chat masih bisa dikirimi pesan atau sudah selesai
            $table->enum('status', ['open', 'closed'])->default('open');
            
            // Tambahan: Waktu terakhir ada pesan masuk (untuk fitur pengurutan di Dashboard Admin)
            $table->timestamp('last_message_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};