@extends($isAdmin ? 'backend.layouts.master' : 'user.layouts.master')

@section('main-content')
<div class="card">
    <h5 class="card-header">Chat with {{ auth()->user()->role === 'admin' ? $chat->user->name : $chat->admin->name }}</h5>
    <div class="card-body">
        <div class="chat-widget">
            <div class="chat-messages" id="chat-messages">
                @foreach($chat->messages as $message)
                    <div class="message {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}">
                        <p>{{ $message->message }}</p>
                        <span class="time">{{ $message->created_at->format('h:i A') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="chat-input mt-3">
                <form id="message-form" style="text-align: right;">
                    @csrf
                    <input type="text" id="message-input" class="form-control" placeholder="Type a message...">
                    <button type="submit" class="btn btn-primary mt-2">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
    .sent {
        text-align: right;
    }
    .message p {
        background-color: #f8f9fa;
        padding: 10px;
        margin-bottom: 0;
        display: inline-block;
    }
    .message span {
        display: block;
        font-size: 12px;
        margin-top: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    const chatId = '{{ $chat->id }}';
    const messagesDiv = $('#chat-messages');
    messagesDiv.scrollTop(messagesDiv[0].scrollHeight);

    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        const messageInput = $('#message-input');
        const message = messageInput.val();

        if (message.trim() === '') return;

        $.ajax({
            url: `/chats/${chatId}/messages`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                message: message
            },
            success: function(response) {
                const messageHtml = `
                    <div class="message sent">
                        <p>${response.message}</p>
                        <span class="time">${new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })}</span>
                    </div>
                `;
                messagesDiv.append(messageHtml);
                messageInput.val('');
                messagesDiv.scrollTop(messagesDiv[0].scrollHeight);
            }
        });
    });

    // Mark messages as read
    $.post(`/chats/${chatId}/read`);

    // You might want to add real-time updates using Laravel Echo here
});
</script>
@endpush