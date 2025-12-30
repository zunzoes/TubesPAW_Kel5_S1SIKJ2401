<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Menampilkan halaman chat customer.
     */
    public function index()
    {
        // Mengambil sesi chat aktif milik customer
        $chat = Chat::with(['messages', 'admin'])
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        // Jika tidak ada chat aktif, kirim null agar view menampilkan placeholder
        return view('customer.chats.index', compact('chat'));
    }

    /**
     * Mengirim pesan baru.
     * Secara otomatis membuat sesi chat jika belum ada.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            DB::beginTransaction();

            // 1. Dapatkan atau buat sesi chat aktif
            $chat = Chat::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'status' => 'open'
                ],
                [
                    'last_message_at' => now()
                ]
            );

            // 2. Simpan pesan ke tabel chat_messages
            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => auth()->id(),
                'message' => $validated['message'],
                'is_from_admin' => false, // Pesan dari customer
                'is_read' => false,
            ]);

            // 3. Update aktivitas terakhir di tabel chats & tandai pesan admin sebagai terbaca
            $chat->updateLastActivity();
            $chat->messages()
                ->where('is_from_admin', true)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            DB::commit();
            return back();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }
    }
}