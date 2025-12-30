@extends('layouts.customer')

@section('title', 'Chat Support - Apparify')

@section('content')
<div class="container pb-5">
    {{-- Ikon dihapus dari judul utama sesuai permintaan --}}
    <div class="mb-4">
        <h2 class="mb-0 fw-bold" style="color: var(--dark-text)">Customer Support Chat</h2>
        <p class="text-muted small">Konsultasikan kebutuhan apparel Anda secara langsung dengan tim kami.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="height: 75vh; border-radius: 20px; overflow: hidden;">
                @if(isset($chat) && $chat->status == 'open')
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <div class="bg-light rounded-circle p-2">
                                        <i class="fas fa-headset fa-lg" style="color: var(--primary)"></i>
                                    </div>
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Apparify Support Team</h6>
                                    <small class="text-success small"><i class="fas fa-circle small me-1" style="font-size: 8px;"></i> Online</small>
                                </div>
                            </div>
                            <span class="badge rounded-pill px-3" style="background-color: var(--primary); color: white;">Active Session</span>
                        </div>
                    </div>

                    <div class="card-body px-4 py-4" style="overflow-y: auto; background-color: #f8f9fa;" id="messages-container">
                        @if($chat->messages->count() > 0)
                            @foreach($chat->messages as $message)
                                <div class="d-flex {{ $message->is_from_admin ? 'justify-content-start' : 'justify-content-end' }} mb-4">
                                    <div style="max-width: 75%;">
                                        <div class="card border-0 shadow-sm {{ $message->is_from_admin ? 'bg-white' : 'bg-custom-primary text-white' }}" 
                                             style="border-radius: {{ $message->is_from_admin ? '0 15px 15px 15px' : '15px 0 15px 15px' }};">
                                            <div class="card-body py-2 px-3">
                                                @if($message->is_from_admin)
                                                    <small class="d-block mb-1 fw-bold" style="font-size: 0.7rem; color: var(--primary);">
                                                        ADMIN TEAM
                                                    </small>
                                                @endif
                                                <p class="mb-1" style="font-size: 0.95rem; line-height: 1.4;">{{ $message->message }}</p>
                                                <div class="d-flex align-items-center {{ $message->is_from_admin ? 'text-muted' : 'text-white-50' }}" style="font-size: 0.65rem;">
                                                    <span>{{ $message->created_at->format('H:i') }}</span>
                                                    @if(!$message->is_from_admin)
                                                        <i class="fas fa-check-double ms-1 {{ $message->is_read ? 'text-info' : '' }}"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="card-footer bg-white border-top p-3">
                        <form action="{{ route('customer.chat.sendMessage') }}" method="POST" id="message-form">
                            @csrf
                            <div class="input-group rounded-pill px-3 py-1 border shadow-sm">
                                <input type="text" class="form-control border-0 bg-transparent py-2 shadow-none" 
                                       name="message" id="message-input"
                                       placeholder="Type your message..." required autocomplete="off">
                                <button type="submit" class="btn btn-link p-0 ms-2" style="color: var(--primary)">
                                    <i class="fas fa-paper-plane fa-lg"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- Layar Awal Chat --}}
                    <div class="card-body d-flex align-items-center justify-content-center bg-white">
                        <div class="text-center" style="max-width: 400px;">
                            <h4 class="fw-bold mb-3" style="color: var(--dark-text)">Ada yang bisa kami bantu?</h4>
                            <p class="text-muted mb-4">Konsultasikan desain kustom Anda atau tanyakan status pesanan langsung kepada tim support kami.</p>
                            
                            <form action="{{ route('customer.chat.sendMessage') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control border shadow-sm p-3" name="message" rows="3" required
                                              style="border-radius: 15px; background-color: #fcfcfc;"
                                              placeholder="Tuliskan pertanyaan Anda di sini..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm rounded-pill fw-bold" style="background-color: var(--primary); border: none;">
                                    <i class="fas fa-paper-plane me-2"></i> Start Chat
                                </button>
                            </form>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-center gap-3">
                                    <small class="text-muted"><i class="fas fa-clock me-1" style="color: var(--primary)"></i> Respon < 10 Menit</small>
                                    <small class="text-muted"><i class="fas fa-shield-alt me-1" style="color: var(--primary)"></i> Aman & Privat</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Support Center Icons --}}
            <div class="row mt-4 g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center py-3" style="border-radius: 15px;">
                        <div class="card-body">
                            <i class="fas fa-question-circle fa-2x mb-3" style="color: var(--primary)"></i>
                            <h6 class="fw-bold">FAQ Center</h6>
                            <p class="small text-muted mb-0">Jawaban cepat untuk pertanyaan umum.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center py-3" style="border-radius: 15px;">
                        <div class="card-body">
                            <i class="fas fa-truck fa-2x text-success mb-3"></i>
                            <h6 class="fw-bold">Track Order</h6>
                            <p class="small text-muted mb-0">Cek status pengiriman pesananmu.</p>
                            <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-success mt-2 rounded-pill">Lacak Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center py-3" style="border-radius: 15px;">
                        <div class="card-body">
                            <i class="fas fa-envelope fa-2x text-info mb-3"></i>
                            <h6 class="fw-bold">Email Support</h6>
                            <p class="small text-muted mb-0">support@apparify.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-custom-primary {
        background-color: var(--primary) !important;
    }
    #messages-container::-webkit-scrollbar {
        width: 6px;
    }
    #messages-container::-webkit-scrollbar-thumb {
        background-color: rgba(0,0,0,0.1);
        border-radius: 10px;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        const messageInput = document.getElementById('message-input');
        if (messageInput) {
            messageInput.focus();
        }

        const form = document.getElementById('message-form');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('button');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            });
        }
    });
</script>
@endpush