<?php

namespace GreyZero\WebCallCenter\Controllers;

use Willywes\AgoraSDK\RtcTokenBuilder;

class AuthController extends Controller{
    public function getLogin(){
        return view('wcc::login');
    }

    public function postLogin(){
        if(auth()->attempt(request()->only(['username', 'password'])))
            return redirect(config('web-call-center.prefix').'/'.auth()->user()->role);
        return view('wcc::login', ['fail' => true]);
    }

    public function getLogout(){
        auth()->logout();
        return redirect()->route('wcc.login');
    }

    public function getRtcToken(){
        $authenticatedUser = auth()->user();
        $prefix = ($authenticatedUser->role == 'agent')? 'a' : 'c';
        $currentUserId = sha1("$prefix-{$authenticatedUser->authenticatable_id}");
        $channel = 'rtc-'.($authenticatedUser->role == 'agent'? $currentUserId : request('a'));
        return response()->json([
            'rtc_token' => cache()->remember("$channel-$prefix-{$authenticatedUser->authenticatable_id}",
                now()->addHours(2), fn() => RtcTokenBuilder::buildTokenWithUserAccount(env('AGORA_APP_ID'),
                    env('AGORA_CERTIFICATE'), $channel, $currentUserId, RtcTokenBuilder::RoleAttendee, time() + 60 * 60 * 2)),
            'user_id' => $currentUserId,
            'debug' => [
                'user' => $authenticatedUser
            ]
        ] + compact('channel'));
    }
}
