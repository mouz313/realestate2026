<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deal_id' => 'required|exists:deals,id',
            'agent_id' => 'required|exists:agents,id',
            'type' => 'required|string|in:percentage,fixed',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,approved,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
