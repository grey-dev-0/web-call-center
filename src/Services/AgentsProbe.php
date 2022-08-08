<?php

namespace GreyZero\WebCallCenter\Services;

class AgentsProbe{
    /**
     * Creates a service that can be used to get stats of online agents and / or organizations.
     *
     * @param \Pusher\Pusher $pusher
     * @throws \Pusher\PusherException
     */
    public function __construct(private \Pusher\Pusher $pusher){}

    /**
     * Returns the IDs of the currently online agents in the given organization.
     *
     * @param int $organizationId The ID of the organization which to get its online agents.
     * @return int[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pusher\ApiErrorException
     * @throws \Pusher\PusherException
     */
    public function getOnlineAgents($organizationId){
        return collect($this->pusher->get("/channels/presence-organization.$organizationId/users")->users?? [])->pluck('id')->toArray();
    }
}
