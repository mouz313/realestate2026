<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentPayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agent_id' => 'required|exists:agents,id',
            'amount' => 'required|numeric|min:0',
            'payout_date' => 'required|date',
            'method' => 'nullable|string|max:50',
            'commission_ids' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
