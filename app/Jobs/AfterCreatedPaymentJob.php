<?php

namespace App\Jobs;

use App\Payment;
use Carbon\Carbon;
use App\Helpers\HttpWebhook;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AfterCreatedPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pid;
    protected $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(String $paymentId)
    {
        $this->pid = $paymentId;
        $this->payment = Payment::find($paymentId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $minutes = 1440;
        $path =  env('EXCHANGE_CURRENCY_PATH');
        $today = Carbon::today("America/Santiago")
            ->addHours(3)
            ->format('Y-m-d\TH:i:s.000\Z');
        
        $currency = Cache::remember(
            "prices_{$today}",
            $minutes,
            function () use ($path, $today) {
                $response = HttpWebhook::makeRequest("GET", $path);
                $prices = [];
                if ($response->serie) {
                    // Filter
                    $prices = array_filter(
                        $response->serie,
                        function ($data, $key) use ($today) {
                            return $data->fecha === $today;
                        },
                        ARRAY_FILTER_USE_BOTH
                    );
                }
                dump("{$today} consultando...");
                return end($prices);
            }
        );

        if ($currency) {
            $this->payment->update(['clp_usd' => $currency->valor]);
        }
    }

    /**
     * Queue function
     *
     * @param Object $queue
     * @param Object $command
     *
     * @return Queue
     */
    public function queue($queue, $command)
    {
        $payload = json_encode([
            'job' => 'Illuminate\Queue\CallQueuedHandler@call',
            'data' => [
                'commandName' => get_class($command),
                'command' => serialize(clone $command)
            ],
        ]);
          
        return Queue::connection('jobs')->pushRaw($payload, 'jobs');
    }
}
