<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionReportRequest extends FormRequest
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
            'fromDate' => 'required|date|date_format:Y-m-d|before_or_equal:toDate',
            'toDate' => 'required|date|date_format:Y-m-d|after_or_equal:fromDate',
            'merchant' => 'required|integer|exists:merchants,id',
            'acquirer' => 'required|integer|exists:acquirers,id',
        ];
    }
}
