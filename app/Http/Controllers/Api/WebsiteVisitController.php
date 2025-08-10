<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WebsiteVisit\WebsiteVisitStoreRequest;
use App\Http\Resources\Api\WebsiteVisit\WebsiteVisitResource;
use App\Http\Service\WebsiteVisit\WebsiteVisitService;
use App\Models\Admin\WebsiteVisit;
use Illuminate\Http\Request;

class WebsiteVisitController extends Controller
{
    public function recordVisit(WebsiteVisitStoreRequest $request)
    {
        $ipAddress =  $request->input('ip_address');
        $userAgent =  $request->input('user_agent');

         $existingVisit = WebsiteVisit::where('ip_address', $ipAddress)
            ->where('visited_at', '>=', now()->subDay())
            ->exists();

        if (!$existingVisit) {
            WebsiteVisit::create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'visited_at' => now(),
            ]);
        }
        return response()->json(['message' => 'Visit recorded successfully'], 201);
    }

    public function getStats()
    {
        $totalVisits = WebsiteVisit::count();
        $todaysVisits = WebsiteVisit::whereDate('visited_at', now()->toDateString())->count();
        $distinctUsers = WebsiteVisit::distinct('ip_address')->count('ip_address');

        return response()->json([
            'total_visits' => $totalVisits,
            'todays_visits' => $todaysVisits,
            'distinct_users' => $distinctUsers,
        ]);
    }
}
