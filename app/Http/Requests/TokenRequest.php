<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deal_id' => 'required|exists:deals,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'received_date' => 'nullable|date',
            'status' => 'nullable|string|in:received,pending,cancelled',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
