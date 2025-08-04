<?php

use App\Enums\Statuses\PaymentScheduleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_detail_id')->constrained('payment_details');
            $table->date('due_date')->nullable();
            $table->decimal('due_amount', 10, 2);
            $table->string('status')->default(PaymentScheduleStatus::PENDING->value);
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->date('date_payment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('payment_schedules');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
