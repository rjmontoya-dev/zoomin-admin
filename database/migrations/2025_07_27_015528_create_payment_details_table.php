<?php

use App\Enums\Statuses\PaymentStatus;
use App\Enums\Types\PaymentType;
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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_type')->default(PaymentType::FULL());
            $table->string('status')->default(PaymentStatus::UNPAID());
            $table->integer('number_installments')->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0)->nullable();
            $table->decimal('balance', 10, 2)->default(0);
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
        Schema::dropIfExists('payment_details');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
