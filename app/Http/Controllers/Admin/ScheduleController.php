<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Statuses\ProjectStatus;
use App\Enums\Statuses\RepairStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin\Project;
use App\Models\Admin\Repair;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/schedules/Index');
    }

    public function schedules()
    {
       $projects = Project::with('user')->whereNotIn('status',[ProjectStatus::COMPLETED(), ProjectStatus::WAITING_SCHEDULE()])
       ->select('id', 'status', 'client_name as title', 'start_date as start', 'end_date as end', 'user_id', 'project_code')->get()->map(function ($project) {
           return [
                'id' => $project->id,
                'status' => $project->status?->pill() ?? null,
                'title' => $project->title ?? 'N/A',
                'start' => $project->start ?? null,
                'end' => $project->end ?? null,
                'type' => 'project',
                'user' => $project->user?->name ??  null,
                'project_code' => $project->project_code ?? null,
            ];
        });

       $repairs = Repair::with('project.user')
        ->whereHas('project')
        ->whereNot('status',RepairStatus::DONE())
        ->select('project_id', 'status', 'repair_date as start')
        ->get()
        ->map(function ($repair) {
            return [
                'id' => $repair->project_id,
                'status' => $repair->status?->pill() ?? null,
                'title' => $repair->project->client_name ?? 'N/A',
                'start' => $repair->start ?? null,
                'end' => $repair->end ?? null,
                'type' => 'repair',
                'user' => $repair->project->user?->name ?? null,
                'project_code' => $repair->project?->project_code ?? null,
            ];
        });

        return response()->json($projects->concat($repairs));
    }
}
