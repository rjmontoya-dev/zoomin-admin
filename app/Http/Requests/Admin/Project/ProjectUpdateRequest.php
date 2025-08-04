<?php

namespace App\Http\Requests\Admin\Project;

use App\Enums\Statuses\PaymentStatus;
use App\Enums\Types\BusinessType;
use App\Enums\Types\PaymentType;
use App\Rules\Admin\Project\MatchInstallmentsCount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\RequiredIf;

class ProjectUpdateRequest extends FormRequest
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
            'project_code' => ['required', 'string', 'max:255', Rule::unique('projects')->ignore($this->route('project'))],
            'client_name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'contact_person' => ['nullable', 'string'],
            'province' => ['nullable', 'string'],
            'town' => ['nullable', 'string'],
            'barangay' => ['nullable', 'string'],
            'business_type' => ['required', new Enum(BusinessType::class)],
            'contact_number' => ['nullable', 'string'],
            'fb_name' => ['nullable', 'string'],
            'project_acquired' => ['nullable', 'date'],
            'start_date' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'tech_warranty' => ['nullable', 'date'],
            'hardware_warranty' => ['nullable', 'date'],
            'expenses' => ['required', 'numeric'],
            'project_detail' => ['nullable', 'string'],
            'user_id' => ['required', 'exists:users,id'],

            // Payment Details (nested validation)
            'payment_details.total_amount' => ['required', 'numeric'],
            'payment_details.payment_type' => ['required',  new Enum(PaymentType::class)], // assumes enum-like values (e.g. FULL=1, INSTALLMENT=2)
            'payment_details.number_installments' => ['nullable', 'integer', Rule::requiredIf(
                fn () => $this->input('payment_details.payment_type') === PaymentType::INSTALLMENT()
            ), ],
            'payment_details.status' => ['required', new Enum(PaymentStatus::class)], // Define enum if needed
            'payment_details.amount_paid' => ['required', 'numeric'],
            'payment_details.balance' => ['required', 'numeric'],
            'payment_details.date_payment' => ['required', 'date'],
            'payment_details.remarks' => ['nullable', 'string'],
            'payment_details.payment_schedules' => ['nullable', 'array', new MatchInstallmentsCount()],
            'payment_details.payment_schedules.*.amount_paid' => ['nullable', 'numeric'],
            'payment_details.payment_schedules.*.remarks' => ['nullable', 'string'],
            'payment_details.payment_schedules.*.date_payment' => ['nullable', 'date'],
            'payment_details.payment_schedules.*.due_date' => ['required_with:payment_details.payment_schedules', 'date'],
            'payment_details.payment_schedules.*.due_amount' => ['required_with:payment_details.payment_schedules', 'numeric'],
            'payment_details.payment_schedules.*.status' => ['required_with:payment_details.payment_schedules', 'numeric'],
        ];
    }

}
