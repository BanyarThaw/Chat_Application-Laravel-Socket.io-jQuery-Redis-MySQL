<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\MessageGroup;
use App\Models\MessageGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $group = MessageGroup::find($request->route('message_group'));

        $group_member = MessageGroupMember::where('message_group_id',$request->route('message_group'))
                            ->where('user_id',Auth::user()->id)->first();

        if($group->user_id == Auth::user()->id || isset($group_member)) {
            return $next($request);
        } else {
            return redirect()->back()->with('info','You are not included in this group.');
        }
    }
}
