<?php

namespace App\Http\Transformers;

class PaymentTransformer extends Transformer
{
    /**
     * Transformer data Response
     *
     * @param Client $data
     * @return array
     */
    public function transform($data)
    {
        return [
            'uuid' => $data->uuid,
            'payment_date' => $data->payment_date,
            'expires_at' => $data->expires_at,
            'status' => $data->status,
            'user_id' => $data->user_id,
            'clp_usd' => $data->clp_usd
        ];
    }
}
