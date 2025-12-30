<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'is_from_admin', // Ditambahkan agar sesuai dengan migrasi terbaru
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_from_admin' => 'boolean', // Memastikan nilai 0/1 di DB menjadi true/false di PHP
    ];

    // Relationships

    /**
     * Relasi ke sesi Chat utama.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relasi ke pengirim pesan (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods

    /**
     * Mengecek apakah pesan dikirim oleh Admin.
     * Menggunakan kolom is_from_admin lebih cepat daripada mengecek relasi user.
     */
    public function isFromAdmin()
    {
        return $this->is_from_admin === true;
    }

    /**
     * Mengecek apakah pesan dikirim oleh Customer.
     */
    public function isFromCustomer()
    {
        return $this->is_from_admin === false;
    }

    /**
     * Menandai pesan tunggal ini sebagai sudah dibaca.
     */
    public function markAsRead()
    {
        return $this->update(['is_read' => true]);
    }

    /**
     * Scope untuk mengambil pesan yang belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}