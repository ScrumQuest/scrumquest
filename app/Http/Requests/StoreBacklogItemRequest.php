<?php

namespace App\Http\Requests;

use App\Models\BacklogItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreBacklogItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [BacklogItem::class, $this->route()->parameter('project')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
