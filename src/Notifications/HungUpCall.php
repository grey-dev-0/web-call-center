<?php

namespace GreyZero\WebCallCenter\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class HungUpCall extends Notification{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param \GreyZero\WebCallCenter\Models\Call $call The incoming customer's call.
     * @return void
     */
    public function __construct(public \GreyZero\WebCallCenter\Models\Call $call){}

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable){
        return ['broadcast'];
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(){
        return 'calls.ended';
    }

    /**
     * Creates the broadcast notification message to be sent.
     *
     * @param mixed $notifiable The notified agent.
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable){
        return new BroadcastMessage(['call' => ['id' => $this->call->id]]);
    }
}
