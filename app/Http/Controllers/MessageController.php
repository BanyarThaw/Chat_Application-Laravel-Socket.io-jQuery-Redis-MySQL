<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageEvent;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\PrivateMessageEvent;
use App\Models\MessageGroup;
use App\Models\UserMessage;

class MessageController extends Controller
{
    public function conversation($userId) {
        $users = User::where('id','!=',Auth::id())->get(); 
        $friendInfo = User::findOrFail($userId);       
        $myInfo = User::find(Auth::id());
        $groups = MessageGroup::get();
        $messages = UserMessage::whereIn('sender_id',[Auth::id(),$userId])
                            ->whereIn('receiver_id',[Auth::id(),$userId])
                            ->get();

        return view('message.conversation',compact('users','friendInfo','myInfo','groups','messages'));
    }

    public function sendMessage(Request $request) {
        $request->validate([
            'message' => 'required',
            'receiver_id' => 'required'
        ]);

        $sender_id = Auth::id();
        $receiver_id = $request->receiver_id;

        $message = new Message;
        $message->message = $request->message;

        if($message->save()) {
            try {
                $message->users()->attach($sender_id,['receiver_id' => $receiver_id]);
                $sender = User::where('id','=',$sender_id)->first();

                $data = [];
                $data['sender_id'] = $sender_id;
                $data['sender_name'] = $sender->name;
                $data['receiver_id'] = $receiver_id;
                $data['content'] = $message->message;
                $data['created_at'] = $message->created_at;
                $data['message_id'] = $message->id;
                $data['type'] = 1;

                event(new PrivateMessageEvent($data));

                return response()->json([
                    'data' => $data,
                    'success' => true,
                    'message' => 'Message sent successfully'
                ]);
            } catch(\Exception $e) {
                $message->delete();
            }
        }
    }

    public function sendGroupMessage(Request $request) {
        $request->validate([
            'message' => 'required',
            'message_group_id' => 'required'
        ]);

        $sender_id = Auth::id();
        $messageGroupId = $request->message_group_id;

        $message = new Message;
        $message->message = $request->message;

        if($message->save()) {
            try {
                $message->users()->attach($sender_id,['message_group_id' => $messageGroupId]);
                $sender = User::where('id','=',$sender_id)->first();

                $data = [];
                $data['sender_id'] = $sender_id;
                $data['sender_name'] = $sender->name;
                $data['content'] = $message->message;
                $data['created_at'] = $message->created_at;
                $data['message_id'] = $message->id;
                $data['group_id'] = $messageGroupId;
                $data['type'] = 2;

                event(new GroupMessageEvent($data));

                return response()->json([
                    'data' => $data,
                    'success' => true,
                    'message' => 'Message sent successfully'
                ]);
            } catch(\Exception $e) {
                $message->delete();
            }
        }
    }
}
