@extends('layouts.app')

@section('head-css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"/>
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="row chat-row">
        <div class="col-md-9 chat-section">
            @include('partials.warning')

            @component('partials.message')
                @slot('id')
                    {{ $friendInfo->id }}
                @endslot
                @slot('heading')
                    <div class="chat-image">
                        {!! makeImageFromName($friendInfo->name) !!}
                    </div>
                    <div class="chat-name font-weight-bold">
                        {{ $friendInfo->name }}  
                        <i class="fa fa-circle user-status-head user-icon-{{ $friendInfo->id }}" title="away" id="userStatusHead{{$friendInfo->id}}"></i>                              
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
        <div id="users-list-parent" class="col-lg-3 col-md-3">
            @include('partials.users')
        </div>
        @include('partials.groups')
    </div>

    @include('partials.modal')

    @component('partials.info')
        @slot('conversation_type')
            receiver_id
        @endslot
        @slot('partner_id')
            {{ $friendInfo->id }}
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
            socket.on("private-channel:App\\Events\\PrivateMessageEvent", function (message)
            {
                html_design('',message.sender_name,dateFormat(message.created_at),timeFormat(message.created_at),message.content,message.type,message.sender_id);
            });
        });
    </script>
@endsection
