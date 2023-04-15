<div class="users">
    <ul class="list-group list-chat-item">
        @if($users->count())
            <li class="chat-user-list list-group-item">
                <h5>Users</h5>
            </li>
            <div class="users-list-home-page">
                @foreach($users as $user)
                    <li class="chat-user-list list-group-item">
                        @component('partials.user_info')
                            @slot('id')
                                {{ $user->id }}    
                            @endslot
                            @slot('user_image')
                                {!! makeImageFromName($user->name) !!}
                            @endslot
                            @slot('name')
                                {{ $user->name }}  
                            @endslot
                        @endcomponent
                    </li>
                @endforeach
            </div>
        @endif
    </ul>
</div>