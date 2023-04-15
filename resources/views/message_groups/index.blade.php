@extends('layouts.app')

@section('head-css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}" />
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="row chat-row">
        <div class="col-lg-6 col-md-6 chat-section">
            @include('partials.warning')
            
            @component('partials.message')
                @slot('id')
                    gp-{{ $currentGroup->id }}
                @endslot
                @slot('heading')
                    <div class="chat-image">
                        {{ $currentGroup->name }}
                    </div>
                @endslot
                @slot('messages')
                    @foreach($messages as $message)
                        <div class="row message align-items-center mb-2">
                            <div class="col-md-12 user-info">
                                <div class="chat-image">
                                    
                                </div>
                                <div class="chat-name font-weight-bold">
                                    {{ $message->sender->name }}
                                    <span class="small time text-gray-500" title="2020-05-06 10:30 PM">
                                        {{ $message->created_at->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 message-content">
                                <div class="message-text">
                                    {{ $message->message->message }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endslot
            @endcomponent
        </div>

        <div id="current-group-parent" class="col-lg-3 col-md-3">
            @if(isset($currentGroup->message_group_members) && !empty($currentGroup->message_group_members))
                <ul class="list-group list-chat-item">
                    <li class="chat-user-list list-group-item">
                        <h5>{{ $currentGroup->name }}</h5>
                    </li>
                    <div class="current-group">
                        <li class="list-group-item">
                            @component('partials.user_info')
                                @slot('id')
                                    {{ $currentGroup->user_id }}    
                                @endslot
                                @slot('user_image')
                                    {!! makeImageFromName($currentGroup->group_admin->name) !!}
                                @endslot
                                @slot('name')
                                    {{ $currentGroup->group_admin->name }}
                                    <i class="fa fa-cog"></i>
                                @endslot
                            @endcomponent
                        </li>
                        @foreach($currentGroup->message_group_members as $member)
                            @if(isset($member->user))
                                <li class="list-group-item">
                                    @component('partials.user_info')
                                        @slot('id')
                                            {{ $member->user->id }}    
                                        @endslot
                                        @slot('user_image')
                                            {!! makeImageFromName($member->user->name) !!}
                                        @endslot
                                        @slot('name')
                                            {{ $member->user->name }}
                                        @endslot
                                    @endcomponent
                                </li>
                            @endif
                        @endforeach
                    </div>
                </ul>
            @endif
        </div>

        <div id="users-list-parent" class="col-lg-3 col-md-3">
            @include('partials.users')
        </div>

        @include('partials.groups')
    </div>

    @include('partials.modal')

    @component('partials.info')
        @slot('conversation_type')
            message_group_id
        @endslot
        @slot('partner_id')
            {!! $currentGroup->id !!}
        @endslot
        @slot('sender_name')
            {{ $myInfo->name }}
        @endslot
    @endcomponent
@endsection

@section('script')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/message.js') }}"></script>
    <script>
        $(function() {
            socket.on('connect', function() {
                let data = {
                    group_id: "{!! $currentGroup->id !!}",
                    user_id: user_id,
                    room: "group" + "{!! $currentGroup->id !!}"
                };
                socket.emit('joinGroup', data);
            });

            socket.on("groupMessage", function(message) {
                html_design('', message.sender_name, dateFormat(message.created_at), timeFormat(message.created_at), message.content,message.type,message.group_id);
            });
        });
    </script>
@endsection