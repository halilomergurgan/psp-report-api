<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fromDate' => 'nullable|date_format:Y-m-d',
            'toDate' => 'nullable|date_format:Y-m-d',
            'merchantId' => 'nullable|integer',
            'acquirerId' => 'nullable|integer',
            'status' => 'nullable|in:APPROVED,WAITING,DECLINED,ERROR',
            'operation' => 'nullable|in:DIRECT,REFUND,3D,3DAUTH,STORED',
            'paymentMethod' => 'nullable|in:CREDITCARD',
            'errorCode' => 'nullable|in:Do not honor,Invalid Transaction,Invalid Card,Not sufficient funds,Incorrect PIN,Invalid country association,Currency not allowed,3-D Secure Transport Error,Transaction not permitted to cardholder',
            'filterField' => 'nullable|in:Transaction UUID,Customer Email,Reference No,Custom Data,Card PAN',
            'filterValue' => 'nullable|string',
            'page' => 'nullable|integer'
        ];
    }

    /**
     * @param $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->all() === []) {
                $validator->errors()->add('filters', 'At least one filter field must be provided.');
            }
        });
    }
}
