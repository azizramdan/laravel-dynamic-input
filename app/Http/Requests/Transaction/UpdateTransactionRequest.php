<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => ['required', 'date'],
            'customer_name' => ['required', 'string'],
            'status' => ['required', 'in:cash,credit'],
            'category_id' => ['required', 'array'],
            'category_id.*' => ['required', 'numeric'],
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string'],
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'numeric'],
            'unit' => ['required', 'array'],
            'unit.*' => ['required', 'in:Kg,Ons'],
            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric'],
        ];
    }
}
