<?php

use App\Enums\Statuses\ProjectStatus;
use App\Enums\Types\BusinessType;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->string('client_name');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default(ProjectStatus::IN_PROGRESS->value);
            $table->string('contact_person')->nullable();
            $table->string('province')->nullable();
            $table->string('town')->nullable();
            $table->string('barangay')->nullable();
            $table->string('business_type')->default(BusinessType::PUBLIC->value);
            $table->string('contact_number')->nullable();
            $table->string('fb_name')->nullable();
            $table->date('project_acquired')->nullable();
            $table->text('project_detail')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('tech_warranty')->nullable();
            $table->date('hardware_warranty')->nullable();
            $table->decimal('expenses', 10, 2);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('projects');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};
