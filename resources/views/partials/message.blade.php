<div class="chat-header">
    {{ $heading }}
</div>
<div class="chat-body" id="chatBody">
    <div class="message-listing" id="messageWrapper-{{ $id }}">
        {{ $messages }}
    </div>
</div>
<div class="chat-box">
    <input type="text" id="chatInput" class="form-control chat-input" placeholder="send a message .....">
    <button type="button" class="btn btn-primary send-button"><i class="fa fa-paper-plane"></i></button>
</div>