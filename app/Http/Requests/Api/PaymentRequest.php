<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{

    /**
     * Prepare data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        //dd($this->all());
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "payment_date" => "nullable|date",
            "expires_at" => "required|date",
            "status" => "required",
            "user_id" => "required",
            "clp_usd" => "nullable",
        ];
    }
}
