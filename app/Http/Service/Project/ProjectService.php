<?php

namespace App\Http\Service\Project;

use App\Enums\Statuses\PaymentStatus;
use App\Enums\Statuses\PaymentScheduleStatus;
use App\Enums\Statuses\ProjectStatus;
use App\Enums\Types\PaymentType;
use App\Http\Service\Base\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectService extends BaseService
{
    public function initialize(): void
    {
        $this->setSearchableFields([
            'project_code',
            'client_name',
            'contact_person',
            'province',
            'town',
            'barangay',
            'fb_name',
        ]);

        $this->setFilters([
            'status' => fn ($query, $value) => $query->where('status', $value),
            'business_type' => fn ($query, $value) => $query->where('business_type', $value),
        ]);

        $this->setSorters([
            'id',
            'project_code',
            'client_name',
            'amount',
            'status',
            'business_type',
            'project_acquired',
            'start_date',
            'end_date',
            'turnover_date',
            'created_at',
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request): \Illuminate\Database\Eloquent\Model
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $request) {
            if($data['start_date']){
                $data['status'] = ProjectStatus::IN_PROGRESS();
            }else{
                $data['status'] = ProjectStatus::WAITING_SCHEDULE();
            }
            $project = $this->model->create($data);

            $paymentDetailData = [
                'project_id' => $project->id,
                'payment_type' => $data['payment_details']['payment_type'],
                'total_amount' => $data['payment_details']['total_amount'],
                'date_payment' => $data['payment_details']['date_payment'],
                'number_installments' => $data['payment_details']['number_installments'] ?? null,
                'remarks' => $data['payment_details']['remarks']
            ];

            if ($paymentDetailData['payment_type'] === PaymentType::FULL->value) {
                $paymentDetailData['status'] = PaymentStatus::PAID->value;
                $paymentDetailData['balance'] = 0;
                $paymentDetailData['amount_paid'] = $data['amount'];
            } else {
                $paymentDetailData['status'] = PaymentStatus::UNPAID->value;
                $paymentDetailData['balance'] = $data['amount'];
                $paymentDetailData['amount_paid'] = 0;
            }

            $paymentDetail = $project->paymentDetail()->create($paymentDetailData);

            if ($paymentDetailData['payment_type'] === PaymentType::INSTALLMENT() && isset($paymentDetailData['number_installments'])) {
                $this->createPaymentSchedules($paymentDetail, $data);
            }

            return $project;
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  Model  $model
     */
    public function update(Request $request, Model $project): Model
    {
        $data = $request->validated();

        return DB::transaction(function () use ( $data, $project) {
            if($data['start_date']){
                $data['status'] = ProjectStatus::IN_PROGRESS();
            }else{
                $data['status'] = ProjectStatus::WAITING_SCHEDULE();
            }
            $project->update($data);

            $paymentDetailData = [
                'project_id' => $project->id,
                'payment_type' => $data['payment_details']['payment_type'],
                'total_amount' => $data['payment_details']['total_amount'],
                'date_payment' => $data['payment_details']['date_payment'],
                'number_installments' => $data['payment_details']['number_installments'] ?? null,
                'remarks' => $data['payment_details']['remarks']
            ];

            if ($paymentDetailData['payment_type'] === PaymentType::FULL->value) {
                $paymentDetailData['status'] = PaymentStatus::PAID->value;
                $paymentDetailData['balance'] = 0;
                $paymentDetailData['amount_paid'] = $data['amount'];
            } else {
                $paymentDetailData['status'] = PaymentStatus::UNPAID->value;
                $paymentDetailData['balance'] = $data['amount'];
                $paymentDetailData['amount_paid'] = 0;
            }

            $paymentDetail = $project->paymentDetail()->updateOrCreate(['project_id' => $project->id], $paymentDetailData);
            
            $paymentDetail->paymentSchedules()->forceDelete();

            if ($paymentDetailData['payment_type'] === PaymentType::INSTALLMENT() && isset($paymentDetailData['number_installments'])) {
                $this->createPaymentSchedules($paymentDetail, $data);
            }

            return $project;
        });
    }

    /**
     * @param  \App\Models\Admin\PaymentDetail  $paymentDetail
     * @param  array  $data
     */
    private function createPaymentSchedules($paymentDetail, $data): void
    {
        $paymentSchedules = $data['payment_details']['payment_schedules'];

        $numberOfInstallments = $paymentDetail->number_installments;
        $totalAmount = $paymentDetail->total_amount;
        $dueDate = Carbon::parse($paymentDetail->date_payment);
        $dueAmount = round($totalAmount / $numberOfInstallments, 2);

        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            $scheduleData = $paymentSchedules[$i - 1] ?? [];

            $paymentDetail->paymentSchedules()->create([
                'amount_paid' => $scheduleData['amount_paid'] ?? null,
                'remarks' => $scheduleData['remarks'] ?? null,
                'date_payment' => $scheduleData['date_payment'] ?? null,
                'due_date' => $dueDate->addMonth()->toDateString(),
                'due_amount' => $dueAmount,
                'status' => $scheduleData['status'] ?? PaymentScheduleStatus::PENDING->value,
            ]);
        }
    }
}
