<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom.
     */
    public function up(): void
    {
        // Tambah kolom ke tabel chats
        Schema::table('chats', function (Blueprint $table) {
            if (!Schema::hasColumn('chats', 'last_message_at')) {
                // Digunakan untuk mengurutkan chat berdasarkan aktivitas terbaru
                $table->timestamp('last_message_at')->nullable()->after('status');
            }
        });

        // Tambah kolom ke tabel chat_messages
        Schema::table('chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_messages', 'is_from_admin')) {
                // Penting untuk membedakan bubble chat antara Customer dan Admin
                $table->boolean('is_from_admin')->default(false)->after('message');
            }
        });
    }

    /**
     * Batalkan migrasi (hapus kolom) jika diperlukan.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            if (Schema::hasColumn('chats', 'last_message_at')) {
                $table->dropColumn('last_message_at');
            }
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'is_from_admin')) {
                $table->dropColumn('is_from_admin');
            }
        });
    }
};