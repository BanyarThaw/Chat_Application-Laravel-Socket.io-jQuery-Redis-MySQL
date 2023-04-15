<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MessageGroup;
use App\Models\MessageGroupMember;
use App\Models\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkUser')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['name'] = $request->name;
        $data['user_id'] = Auth::id();

        $messageGroup = MessageGroup::create($data);

        if($messageGroup) {
            if(isset($request->user_id) && !empty($request->user_id)) {
                foreach($request->user_id as $userId) {
                    $memberData['user_id'] = $userId;
                    $memberData['message_group_id'] = $messageGroup->id;
                    $memberData['status'] = 0;

                    MessageGroupMember::create($memberData);
                }
            }
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::where('id','!=', Auth::id())->get();
        $myInfo = User::find(Auth::id());
        $groups = MessageGroup::get();
        $currentGroup = MessageGroup::where('id',$id)
            ->with('message_group_members.user')
            ->first();
        $messages = UserMessage::where('message_group_id',$id)->get();

        return view('message_groups.index',compact('users','myInfo','groups','currentGroup','messages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
