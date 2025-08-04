<?php

use App\Enums\Statuses\RepairStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('reported_issue');
            $table->date('date_reported');
            $table->string('status')->default(RepairStatus::PENDING->value);
            $table->text('remarks')->nullable();
            $table->date('repair_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('date_finished')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
