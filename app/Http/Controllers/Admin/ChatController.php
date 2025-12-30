<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar inbox chat secara umum.
     */
    public function index()
    {
        // Mengambil semua chat untuk ditampilkan di sidebar
        $chats = Chat::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $activeChat = null;

        return view('admin.chats.index', compact('chats', 'activeChat'));
    }

    /**
     * Menampilkan chat spesifik (Solusi Error image_93a6c7.png).
     */
    public function show(Chat $chat)
    {
        // Tetap ambil semua chat agar sidebar tidak hilang
        $chats = Chat::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Load pesan untuk chat yang dipilih
        $chat->load(['user', 'messages']);

        // Tandai pesan customer sebagai terbaca
        $chat->messages()
            ->where('is_from_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $activeChat = $chat;

        return view('admin.chats.index', compact('chats', 'activeChat'));
    }

    /**
     * Menangani pengiriman balasan dari Admin.
     */
    public function reply(Request $request, Chat $chat)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            DB::beginTransaction();

            // Simpan pesan dengan is_from_admin = true
            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => auth()->id(),
                'message' => $validated['message'],
                'is_from_admin' => true,
                'is_read' => false,
            ]);

            // Update waktu aktivitas terakhir agar chat naik ke atas
            $chat->updateLastActivity();

            DB::commit();
            
            // Redirect kembali ke rute show agar percakapan tetap terbuka
            return redirect()->route('admin.chats.show', $chat->id)
                             ->with('success', 'Pesan terkirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membalas: ' . $e->getMessage());
        }
    }
}