@extends('layouts.app')

@section('content')
    <div class="row chat-row">
        <div class="col-md-9">
            <ul class="list-group list-chat-item">
                <li class="chat-user-list list-group-item">
                    @include('partials.warning')
                    <h1>
                        Message Section
                    </h1>
                </li>
                <li class="chat-user-list list-group-item">
                    Select a user from the list or a group to begin conversation.
                </li>
            </ul>
            <img class="home_page_image" src="{{ asset('images/chat2.png') }}" alt="Chat Image">
        </div>
        <div class="col-md-3">
            @include('partials.users')

            <div id="current-group-parent">
                <br>
                <ul class="list-group list-chat-item">
                    <li class="chat-user-list list-group-item">
                        <h5>Groups</h5>
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
        </div>
    </div>

    @component('partials.info')
        @slot('conversation_type')
            
        @endslot
        @slot('partner_id')
            
        @endslot
        @slot('sender_name')
            
        @endslot
    @endcomponent
@endsection

@section('script')
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
