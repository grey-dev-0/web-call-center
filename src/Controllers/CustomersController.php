<?php

namespace GreyZero\WebCallCenter\Controllers;

use Willywes\AgoraSDK\RtmTokenBuilder;

class CustomersController extends Controller{
    public function getIndex(){
        $customer = auth()->user()->authenticatable;
        return view('wcc::customer', compact('customer') + [
            'rtc' => ['app_id' => env('AGORA_APP_ID'), 'customer_id' => sha1("c-{$customer->id}")]
        ]);
    }

    public function getOrganizations(){
        return response()->json([
            'organizations' => config('web-call-center.organization_model')::paginate(25, ['id', 'name'], 'page', request('p'))
        ]);
    }

    public function postCall(){
        $customer = auth()->user()->authenticatable;
        $token = cache()->remember("rtm-c-{$customer->id}", now()->addDay(),
            fn() => RtmTokenBuilder::buildToken(env('AGORA_APP_ID'), env('AGORA_CERTIFICATE'), sha1("c-{$customer->id}"),
                RtmTokenBuilder::RoleRtmUser, 60 * 60 * 24));
        return response()->json([
            'customer_id' => sha1("c-{$customer->id}"),
            'agent_id' => sha1('a-'.$customer->enqueue(request('id'))->agent_id),
            'rtm_token' => $token
        ]);
    }

    public function getHangup($organization){
        $user = auth()->user();
        $organization = config('web-call-center.organization_model')::find($organization);
        $call = $organization->calls()->whereCustomerId($user->authenticatable_id)->whereNull('ended_at')->first();
        $user->authenticatable->hangup($call);
        return response()->json(['success' => true]);
    }
}
