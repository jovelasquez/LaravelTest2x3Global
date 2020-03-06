<?php

namespace App\Listeners;

use App\Jobs\SendEmailJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AfterCreatedPaymentListener implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'notifications';
    
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'notifications.fifo';

    protected $payment;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->payment = $event->getPaymentModelEvent();

        // Send Notification
        $this->sendNotification();
    }

    /**
     * Queue function
     *
     * @param Queue $queue
     * @param Command $command
     * @param Array $data
     * @return void
     */
    public function queue($queue, $command, $data)
    {
        $payload = json_encode([
            'job' => $command,
            'data' => $data,
            'attempts' => 3,
        ]);

        $dataUnserialize = unserialize($data['data']);
        $model = $dataUnserialize[0]->getPaymentModelEvent();

        $SQSQueue = Queue::connection('notifications');
        $SQSClient = Queue::connection('notifications')->getSqs();

        $response = $SQSClient->sendMessage([
            'MessageGroupId' => 1,
            'MessageDeduplicationId' => uniqid(),
            'QueueUrl' => $SQSQueue->getQueue('notifications.fifo'),
            'MessageBody' => $payload,
        ]);

        return $response->get('MessageId');
    }

    /**
     * SendNotification function
     *
     * @return void
     */
    protected function sendNotification()
    {
        $client = $this->payment->client;

        $job = new SendEmailJob($client);
        $job->onConnection('notifications');
        dispatch($job);
    }
}
