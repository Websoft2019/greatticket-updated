<?php

namespace App\Http\Requests;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSeatsRequest extends FormRequest
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
            'seats' => 'required|array',
            'seats.*.id' => 'nullable|exists:seats,id',
            'seats.*.row_label' => 'required|string|max:10',
            'seats.*.seat_number' => 'required|string|max:10',
            'seats.*.position_x' => 'nullable|integer|min:0',
            'seats.*.position_y' => 'nullable|integer|min:0',
            'seats.*.status' => 'required|in:available,reserved,booked',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'seats.required' => 'At least one seat is required.',
            'seats.*.row_label.required' => 'Row label is required for each seat.',
            'seats.*.seat_number.required' => 'Seat number is required for each seat.',
            'seats.*.status.in' => 'Seat status must be available, reserved, or booked.',
        ];
    }

    // public function withValidator($validator): void
    // {
    //     $validator->after(function ($validator) {
    //         $package = Package::find($this->input('package_id'));

    //         $available = $package->capacity - $package->consumed_seat;

    //         if ($package && count($this->input('seats', [])) > $available) {
    //             $validator->errors()->add('seats', 'The number of seats exceeds the package capacity of ' . $available . '.');
    //         }
    //     });
    // }
}
