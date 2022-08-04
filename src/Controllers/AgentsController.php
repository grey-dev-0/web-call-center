<?php

namespace GreyZero\WebCallCenter\Controllers;

use GreyZero\WebCallCenter\Models\Call;
use Willywes\AgoraSDK\RtmTokenBuilder;

class AgentsController extends Controller{
    public function getIndex(){
        $agent = auth()->user()->authenticatable;
        $token = cache()->remember("rtm-a-{$agent->id}", now()->addDay(),
            fn() => RtmTokenBuilder::buildToken(env('AGORA_APP_ID'), env('AGORA_CERTIFICATE'), sha1("a-{$agent->id}"),
                RtmTokenBuilder::RoleRtmUser, 60 * 60 * 24));
        return view('wcc::agent', compact('agent') + [
            'rtc' => ['app_id' => env('AGORA_APP_ID'), 'agent_id' => sha1("a-{$agent->id}")] + compact('token')
        ]);
    }

    public function getCustomers(){
        return response()->json([
            'customers' => Call::with('customer:id,name')->whereAgentId(auth()->id())->whereNull('ended_at')
                ->orderBy('created_at')->take(25)->get(['id', 'customer_id', 'created_at'])
                ->each(fn($call) => $call->setAttribute('name', $call->customer->name)
                    ->setAttribute('joined_at', $call->created_at->format(config('web-call-center.datetime_format')))
                    ->makeHidden(['customer_id', 'customer', 'created_at']))
        ]);
    }
}
