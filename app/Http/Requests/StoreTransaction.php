<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransaction extends FormRequest
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
            'is_group' => 'required',
            'grand_total' => 'required',
            'pay' => 'required',
            'charge' => 'required',
            'purchase_date' => ['required', 'date'],
            'payment_method' => 'required',
            'tickets' => 'required',
            'note' => ['nullable', 'string'],
            'tickets.*.ticket_type_id' => [
                'required',
                Rule::exists('ticket_types', 'id')->where(function (Builder $query) {
                    $query->where('is_active', 1);
                })
            ],
            'tickets.*.qty' => 'required',
            'tickets.*.price' => 'required',
            'tickets.*.subtotal' => 'required',
            'tickets.*.discount' => 'required',
            'tickets.*.total' => 'required',
        ];
    }
}
