<div class="groups col-lg-12 col-md-12">
    <ul class="list-group list-chat-item">
        <li class="chat-user-list list-group-item">
            <h5>Groups <i class="fa fa-plus btn-add-group ml-3" data-bs-toggle="modal" data-bs-target="#addGroupModal"></i></h5>
        </li>
        @if($groups->count())
            @foreach($groups as $group)
                <li class="chat-user-list list-group-item">
                    <a href="{{ route('message-groups.show', $group->id) }}">
                        {{ $group->name }}
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>