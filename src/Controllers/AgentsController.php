<?php

namespace GreyZero\WebCallCenter\Controllers;

use Willywes\AgoraSDK\RtmTokenBuilder;

class AgentsController extends Controller{
    public function getIndex(){
        $agent = auth()->user()->authenticatable;
        return view('wcc::agent', compact('agent') + [
            'rtc' => ['app_id' => env('AGORA_APP_ID'), 'agent_id' => sha1("a-{$agent->id}"),
                'token' => RtmTokenBuilder::buildToken(env('AGORA_APP_ID'), env('AGORA_CERTIFICATE'), sha1("a-{$agent->id}"),
                    RtmTokenBuilder::RoleRtmUser, 60 * 60 * 24)]
        ]);
    }
}
