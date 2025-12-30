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
        Schema::table('custom_designs', function (Blueprint $table) {
            // Menggunakan nullable() bersifat opsional, namun disarankan jika tabel sudah memiliki data lama
            // agar data lama tidak error karena kolom baru yang bersifat 'required'
            $table->foreignId('product_id')
                  ->after('user_id')
                  ->nullable() // Tambahkan ini jika tabel sudah ada isinya
                  ->constrained('products') // Menegaskan relasi ke tabel products
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_designs', function (Blueprint $table) {
            // Urutan penghapusan: Hapus foreign key dulu, baru hapus kolomnya
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
};