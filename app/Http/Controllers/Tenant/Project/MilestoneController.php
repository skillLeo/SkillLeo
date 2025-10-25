<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    /**
     * Display all milestones list
     */
    public function index($username, $project)
    {
        return view('tenant.manage.projects.milestones.index', compact('username', 'project'));
    }

    /**
     * Display single milestone detail
     */
    public function show($username, $project, $milestone)
    {
        return view('tenant.manage.projects.milestones.show', compact('username', 'project', 'milestone'));
    }
}