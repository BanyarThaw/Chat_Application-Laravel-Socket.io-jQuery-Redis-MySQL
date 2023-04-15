<a href="{{ route('message.conversation', $id) }}">
    <div class="chat-image">
        {{ $user_image }}
        <i class="fa fa-circle user-status-icon user-icon-{{ $id }}" title="away"></i>
    </div>
    <div class="chat-name font-weight-bold">
        {{ $name }}                             
    </div>
</a>