<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    /**
     * Display all sprints list
     */
    public function index($username, $project)
    {
        return view('tenant.manage.projects.sprints.index', compact('username', 'project'));
    }

    /**
     * Display active sprint board
     */
    public function active($username, $project)
    {
        return view('tenant.manage.projects.sprints.active', compact('username', 'project'));
    }

    /**
     * Display sprint planning page
     */
    public function planning($username, $project)
    {
        return view('tenant.manage.projects.sprints.planning', compact('username', 'project'));
    }
}