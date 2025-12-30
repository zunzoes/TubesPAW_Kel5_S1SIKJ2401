@extends('layouts.admin')

@section('title', 'Chats - Apparify')
@section('page-title', 'Customer Support Inbox')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="height: 75vh; border-radius: 15px; background-color: white;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold" style="color: #6096B4;"><i class="fas fa-inbox me-2"></i>Customer Messages</h5>
                </div>
                <div class="card-body p-0" style="overflow-y: auto;">
                    @if(isset($chats) && $chats->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($chats as $chat)
                                <a href="{{ route('admin.chats.show', $chat->id) }}" 
                                   class="list-group-item list-group-item-action border-0 py-3 {{ isset($activeChat) && $activeChat->id == $chat->id ? 'border-start border-4' : '' }}"
                                   style="{{ isset($activeChat) && $activeChat->id == $chat->id ? 'background-color: #FCF8EE; border-color: #6096B4 !important;' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold" style="{{ isset($activeChat) && $activeChat->id == $chat->id ? 'color: #6096B4;' : 'color: #2C3333;' }}">
                                            {{ $chat->user->name }}
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">
                                            {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : $chat->updated_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small text-muted text-truncate me-2" style="max-width: 80%;">
                                            @if($chat->latestMessage)
                                                {{ $chat->latestMessage->is_from_admin ? 'You: ' : '' }}{{ Str::limit($chat->latestMessage->message, 40) }}
                                            @else
                                                <span class="text-italic">No messages yet</span>
                                            @endif
                                        </div>
                                        
                                        @php
                                            $unread = $chat->messages->where('is_from_admin', false)->where('is_read', false)->count();
                                        @endphp
                                        @if($unread > 0)
                                            <span class="badge rounded-pill shadow-sm" style="font-size: 0.65rem; background-color: #6096B4;">{{ $unread }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comment-slash fa-3x mb-3 opacity-25" style="color: #BDCDD6;"></i>
                            <p class="text-muted">Belum ada percakapan masuk.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="height: 75vh; border-radius: 15px; overflow: hidden; background-color: white;">
                @if(isset($activeChat))
                    <div class="card-header bg-white py-3 border-bottom" style="border-color: #EEE9DA !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="background-color: #6096B4; width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color: #2C3333;">{{ $activeChat->user->name }}</h6>
                                    <small class="text-muted">{{ $activeChat->user->email }}</small>
                                </div>
                            </div>
                            <div>
                                <span class="badge px-3 py-2" style="background-color: {{ $activeChat->status == 'open' ? '#93BFCF' : '#BDCDD6' }}; color: white;">
                                    {{ strtoupper($activeChat->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4" style="overflow-y: auto; height: calc(75vh - 160px); background-color: #FCF8EE;" id="messages-container">
                        @foreach($activeChat->messages as $message)
                            <div class="d-flex {{ $message->is_from_admin ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                                <div class="p-3 shadow-sm" 
                                     style="max-width: 70%; border-radius: 15px; {{ $message->is_from_admin ? 'background-color: #6096B4; color: white;' : 'background-color: white; color: #2C3333; border: 1px solid #EEE9DA;' }}">
                                    <p class="mb-1" style="font-size: 0.9rem;">{{ $message->message }}</p>
                                    <div class="d-flex align-items-center {{ $message->is_from_admin ? 'text-white-50' : 'text-muted' }}" style="font-size: 0.65rem;">
                                        <span>{{ $message->created_at->format('H:i') }}</span>
                                        @if($message->is_from_admin)
                                            <i class="fas fa-check-double ms-1 {{ $message->is_read ? 'text-info' : '' }}"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white border-top p-3" style="border-color: #EEE9DA !important;">
                        <form action="{{ route('admin.chats.reply', $activeChat->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control border-0 py-2" style="background-color: #EEE9DA;" name="message" 
                                       placeholder="Ketik balasan untuk {{ $activeChat->user->name }}..." required autocomplete="off">
                                <button type="submit" class="btn px-4 text-white" style="background-color: #6096B4;">
                                    <i class="fas fa-paper-plane me-1"></i> Balas
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center h-100" style="background-color: #FCF8EE;">
                        <div class="text-center" style="max-width: 300px;">
                            <div class="rounded-circle p-4 d-inline-block mb-3" style="background-color: #EEE9DA;">
                                <i class="fas fa-comments fa-3x style="color: #BDCDD6; opacity: 0.7;"></i>
                            </div>
                            <h5 class="fw-bold" style="color: #6096B4;">Pilih Percakapan</h5>
                            <p class="text-muted small">Klik pada salah satu nama di samping untuk membalas pesan dari customer.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(isset($activeChat))
    @push('scripts')
    <script>
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    </script>
    @endpush
@endif
@endsection