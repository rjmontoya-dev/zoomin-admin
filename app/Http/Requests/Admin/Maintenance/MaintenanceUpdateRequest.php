<?php

namespace App\Http\Requests\Admin\Maintenance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaintenanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => ['required', 'string'],
            'image' => ['nullable', 'string'],
            'maintenance_type' => ['required', Rule::in(['immediate', 'scheduled'])],
            'status' => [
                Rule::requiredIf(fn () => $this->maintenance_type === 'immediate'),
                'boolean',
                ],
            'start_at' => Rule::when(
                fn () => $this->maintenance_type === 'scheduled',
                ['required', 'date']
            ),
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }
}
