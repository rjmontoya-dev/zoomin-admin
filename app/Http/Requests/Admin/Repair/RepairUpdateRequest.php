<?php

namespace App\Http\Requests\Admin\Repair;

use Illuminate\Foundation\Http\FormRequest;

class RepairUpdateRequest extends FormRequest
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
            'reported_issue' => ['required', 'string', 'max:255'],
            'date_reported' => ['required', 'date'],
            'status' => ['required', 'numeric'],
            'remarks' => ['nullable', 'string'],
            'repair_date' => ['nullable', 'date', 'after_or_equal:date_reported'],
            'amount' => ['nullable', 'numeric'],
            'date_finished' => ['nullable', 'date', 'after_or_equal:repair_date'],
        ];
    }
}