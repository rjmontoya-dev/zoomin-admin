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
                'status' => $project->status->pill(),
                'title' => $project->title ?? 'N/A',
                'start' => $project->start,
                'end' => $project->end,
                'type' => 'project',
                'user' => $project->user->name,
                'project_code' => $project->project_code,
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
                'status' => $repair->status->pill(),
                'title' => $repair->project->client_name ?? 'N/A',
                'start' => $repair->start,
                'end' => $repair->end,
                'type' => 'repair',
                'user' => $repair->project->user->name,
                'project_code' => $repair->project->project_code,
            ];
        });

        return response()->json($projects->concat($repairs));
    }
}