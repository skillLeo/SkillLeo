<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    /**
     * Display project timeline (Gantt chart)
     */
    public function show($username, $project)
    {
        return view('tenant.manage.projects.timeline', compact('username', 'project'));
    }
}