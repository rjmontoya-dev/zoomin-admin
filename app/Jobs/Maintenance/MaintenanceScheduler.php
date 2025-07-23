<?php

namespace App\Jobs\Maintenance;

use App\Models\Admin\Maintenance\Maintenance;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MaintenanceScheduler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $maintenance;

    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Maintenance $maintenance, string $status)
    {
        $this->maintenance = $maintenance;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->maintenance->update(['status' => $this->status]);
    }
}
