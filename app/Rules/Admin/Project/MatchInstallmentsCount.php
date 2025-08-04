<?php

namespace App\Rules\Admin\Project;

use App\Enums\Types\PaymentType;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchInstallmentsCount implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $paymentDetails = $this->data['payment_details'] ?? [];
        $paymentType = $paymentDetails['payment_type'] ?? null;

        // Only validate if the payment type is INSTALLMENT
        if ($paymentType === PaymentType::INSTALLMENT->value) {
            $installments = (int) ($paymentDetails['number_installments'] ?? 0);
            $schedules = $value ?? [];

            if (!is_array($schedules)) {
                $schedules = [];
            }

            if (count($schedules) !== $installments) {
                $fail("The number of payment schedules (" . count($schedules) . ") must match the number of installments ($installments).");
            }
        }
    }
}
