<?php

namespace App\Http\Transformers;

class ClientTransformer extends Transformer
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
            'id' => $data->id,
            'email' => $data->email,
            'join_date' => $data->join_date
        ];
    }
}
