<?php

namespace App\Http\Requests\Api\WebsiteVisit;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteVisitStoreRequest extends FormRequest
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
            'ip_address' => 'required|string|max:255',
            'user_agent' => 'required|string',
        ];
    }
}
