<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'status',
        'last_message_at', // Wajib ada untuk sinkronisasi aktivitas
    ];

    /**
     * Casting dates agar Laravel otomatis mengubah string database menjadi objek Carbon.
     */
    protected $casts = [
        'last_message_at' => 'datetime', // Memungkinkan penggunaan fungsi waktu
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi ke semua pesan dalam sesi chat ini.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id')->orderBy('created_at', 'asc');
    }

    /**
     * Mendapatkan satu pesan terakhir untuk pratinjau daftar chat.
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latestOfMany();
    }

    // Accessors & Helper methods

    /**
     * Menghitung pesan yang belum dibaca.
     */
    public function getUnreadCountAttribute()
    {
        return $this->messages()->where('is_read', false)->count();
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    /**
     * Menandai semua pesan sebagai terbaca.
     */
    public function markAllAsRead()
    {
        return $this->messages()->where('is_read', false)->update(['is_read' => true]);
    }

    /**
     * Memperbarui timestamp aktivitas terakhir di sesi chat ini.
     */
    public function updateLastActivity()
    {
        return $this->update(['last_message_at' => now()]); // Digunakan saat sendMessage
    }
}