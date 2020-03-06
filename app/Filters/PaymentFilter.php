<?php

namespace App\Filters;

use App\Payment;

class PaymentFilter extends Filter
{
    /**
     * Filter by client id.
     * Get all payment by client id.
     *
     * @param $clientId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function client($clientId)
    {
        return $this->builder->whereUserId($clientId);
    }
}
