<?php

namespace App\Http\Requests;

use App\Models\Package;
use App\Rules\SeatCapacityLimit;
use Illuminate\Foundation\Http\FormRequest;

class StoreSeatsRequest extends FormRequest
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
            'package_id' => ['required', 'exists:packages,id'],
            'seats' => [
                'required',
                'array',
                'min:1',
                new SeatCapacityLimit($this->input('package_id'), count($this->input('seats', []))),
            ],
            'seats.*.row_label' => ['required', 'string'],
            'seats.*.seat_number' => ['required', 'string'],
            'seats.*.position_x' => ['nullable', 'integer', 'min:0'],
            'seats.*.position_y' => ['nullable', 'integer', 'min:0'],
            'seats.*.status' => ['nullable', 'in:available,reserved,booked'],
        ];
    }

    public function messages(): array
    {
        return [
            'package_id.required' => 'Package ID is required.',
            'package_id.exists' => 'The selected package does not exist.',
            'seats.required' => 'At least one seat must be provided.',
            'seats.*.row_label.required' => 'Each seat must have a row label.',
            'seats.*.seat_number.required' => 'Each seat must have a seat number.',
        ];
    }
}
