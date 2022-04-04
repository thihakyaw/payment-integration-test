<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakePaymentRequest extends FormRequest
{
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
            'name_on_card' => 'required|string',
            'email' => 'required|string',
            'amount' => 'required|integer',
            'payment_method' => 'required|string',
            'description' => 'required|string'
        ];
    }
}
