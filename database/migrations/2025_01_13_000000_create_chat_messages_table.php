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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            // Menghubungkan pesan ke sesi chat utama
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
            
            // Pengirim pesan (bisa user atau admin)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->text('message');

            // Penting: Membedakan siapa yang mengirim pesan untuk logika UI (bubble chat)
            $table->boolean('is_from_admin')->default(false);
            
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};