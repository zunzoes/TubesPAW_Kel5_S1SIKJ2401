<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        // 1. Verifikasi kepemilikan agar user tidak bisa mengintip orderan orang lain
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // 2. Jika status pembayaran sudah 'paid', arahkan kembali ke detail order
        if ($order->payment && $order->payment->status === 'paid') {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('info', 'Pembayaran untuk pesanan ini sudah diselesaikan.');
        }

        $order->load('payment');

        return view('customer.payment.show', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:bank_transfer,e-wallet,cod'],
            'payment_details' => ['nullable', 'string'],
        ]);

        $order->payment->update([
            'payment_method' => $validated['payment_method'],
            'payment_details' => $validated['payment_details'],
        ]);

        return back()->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Pastikan pembayaran belum lunas sebelum mengunggah bukti baru
        if ($order->payment->status === 'paid') {
            return back()->with('error', 'Pesanan ini sudah dibayar.');
        }

        $validated = $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'payment_proof.required' => 'Wajib mengunggah foto bukti transfer.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.max' => 'Ukuran gambar maksimal adalah 2MB.'
        ]);

        // Hapus bukti transfer lama di storage jika ada (menghemat ruang penyimpanan)
        if ($order->payment->payment_proof) {
            Storage::disk('public')->delete($order->payment->payment_proof);
        }

        // Simpan file bukti transfer ke folder storage/app/public/payment-proofs
        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        // Update status pembayaran
        $order->payment->update([
            'payment_proof' => $path,
            'status' => 'pending', // Menunggu verifikasi admin
        ]);

        /** * Catatan: Status order diubah menjadi 'paid' untuk memberitahu admin 
         * bahwa pelanggan merasa sudah membayar. Admin nantinya akan mengubah 
         * status ke 'processing' setelah verifikasi manual.
         */
        $order->update(['status' => 'paid']);

        return redirect()->route('customer.orders.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }
}