<?php

namespace App\Http\Controllers\Api;

use App\Payment;
use App\Filters\PaymentFilter;
use App\Http\Paginate\Paginate;
use App\Jobs\AfterCreatedPaymentJob;
use Illuminate\Support\Facades\Event;
use App\Events\AfterCreatedPaymentEvent;
use App\Http\Requests\Api\PaymentRequest;
use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\PaymentTransformer;

class PaymentController extends ApiController
{

    /**
     * Client Controller constructor.
     *
     * @param ClientTransformer $transformer
     */
    public function __construct(PaymentTransformer $transformer, Payment $payment)
    {
        $this->transformer = $transformer;
        $this->payment = $payment;
    }

    /**
     * Index function
     *
     * @return void
     */
    public function index(PaymentFilter $filter)
    {
        $payment = new Paginate($this->payment->filter($filter));
        
        return $this->responseWithPagination($payment);
    }

    /**
     * Store function
     *
     * @param PaymentRequest $request
     * @return Response
     */
    public function store(PaymentRequest $request)
    {
        try {
            $payment = $this->payment->create($request->all());

            AfterCreatedPaymentJob::dispatch($payment->id)
                ->onConnection('jobs')
                ->onQueue('jobs');
            
            event(new AfterCreatedPaymentEvent($payment));
            
            return $this->responseSuccessMessage(trans('payment.created.success'), $payment);
        } catch (Exception $e) {
            return $this->responseNotFound();
        }
    }
}
