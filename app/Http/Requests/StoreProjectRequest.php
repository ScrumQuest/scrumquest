<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'weeks_in_sprint' => ['integer', 'min:1', 'max:4'],
            'amount_of_sprints' => ['nullable', 'integer', 'min:1', 'max:16'],
            'first_sprint_day' => ['date', 'after:yesterday'],
            'expected_workdays_per_week' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}
