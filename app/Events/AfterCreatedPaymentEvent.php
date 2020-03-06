<?php

namespace App\Events;

use App\Client;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterCreatedPaymentEvent
{
    use SerializesModels;

    protected $paymentModelEvent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->paymentModelEvent = $payment;
    }

    /**
     * Get Client Model Event function
     *
     * @return Client
     */
    public function getPaymentModelEvent()
    {
        return $this->paymentModelEvent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * Undocumented function
     */
    public function __wakeup()
    {
        parent::__wakeup();
        
        $this->paymentModelEvent = Payment::find($this->paymentModelEvent->id);
    }
}
