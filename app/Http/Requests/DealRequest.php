<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lead_source' => ['nullable', 'string', Rule::in(array_keys(\App\Helpers\Status::leadSources()))],
            'call_log_id' => 'nullable|exists:call_logs,id',
            'visit_id' => 'nullable|exists:property_visits,id',
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'nullable|exists:clients,id',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_phone' => 'nullable|string|max:50',
            'seller_id' => 'nullable|exists:clients,id',
            'seller_name' => 'nullable|string|max:255',
            'seller_phone' => 'nullable|string|max:50',
            'agent_id' => 'nullable|exists:agents,id',
            'sale_price' => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'agent_commission' => 'nullable|numeric|min:0',
            'agency_share' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:inquiry,visit_scheduled,offer_made,token_received,agreement_signed,in_progress,completed,cancelled',
            'agreement_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
