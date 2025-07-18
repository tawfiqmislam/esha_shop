@extends($isAdmin ? 'backend.layouts.master' : 'user.layouts.master')

@section('main-content')
<div class="card">
    <h5 class="card-header">Chats</h5>
    <div class="card-body">
        @if(auth()->user()->role !== 'admin')
            <div class="mb-3">
                <form action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Start New Chat</button>
                </form>
            </div>
        @endif

        <div class="chat-list">
            @foreach($chats as $chat)
                <a href="{{ route('chat.show', $chat->id) }}" class="chat-item">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h6 class="mb-0">
                                {{ auth()->user()->role === 'admin' ? $chat->user->name : 'Admin' }}
                            </h6>
                            <small class="text-muted">
                                {{ $chat->messages->last() ? Str::limit($chat->messages->last()->message, 30) : 'No messages yet' }}
                            </small>
                        </div>
                        <div>
                            <small class="text-muted">
                                {{ $chat->updated_at->diffForHumans() }}
                            </small>
                            {{-- @if($chat->messages->where('is_read', false)->where('user_id', '!=', auth()->id())->count() > 0)
                                <span class="badge badge-primary">
                                    {{ $chat->messages->where('is_read', false)->where('user_id', '!=', auth()->id())->count() }}
                                </span>
                            @endif --}}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chat-item {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .chat-item:hover {
        background-color: #f8f9fa;
        text-decoration: none;
        color: inherit;
    }
</style>
@endpush

@push('styles')
<style>
    .chat-widget {
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    .chat-messages {
        height: 400px;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    .message {
        margin-bottom: 15px;
        padding: 10px 15px;
        border-radius: 20px;
        max-width: 70%;
        line-height: 1.4;
    }
    .message .time {
        font-size: 0.8rem;
        color: #888;
        display: block;
        margin-top: 5px;
    }
    .message.sent {
        background-color: #dcf8c6;
        align-self: flex-end;
    }
    .message.received {
        background-color: #f1f0f0;
        align-self: flex-start;
    }
    .chat-input {
        display: flex;
        padding: 15px;
        border-top: 1px solid #ddd;
    }
    .chat-input .form-control {
        flex-grow: 1;
        margin-right: 10px;
    }
</style>
@endpush