<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSeatsRequest extends FormRequest
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
            'seat_ids' => [
                'required',
                'array',
                'min:1',
                'max:100' // Reasonable batch limit
            ],
            'seat_ids.*' => [
                'required',
                'integer',
                'min:1',
                'exists:seats,id'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'seat_ids.required' => 'Please select at least one seat to delete.',
            'seat_ids.array' => 'Seat IDs must be provided as an array.',
            'seat_ids.min' => 'Please select at least one seat to delete.',
            'seat_ids.max' => 'You can only delete up to 100 seats at once.',
            'seat_ids.*.required' => 'Each seat ID is required.',
            'seat_ids.*.integer' => 'Seat IDs must be valid numbers.',
            'seat_ids.*.exists' => 'One or more selected seats do not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'seat_ids' => 'selected seats',
            'seat_ids.*' => 'seat ID',
        ];
    }
}
