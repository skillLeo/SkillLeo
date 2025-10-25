<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BacklogController extends Controller
{
    /**
     * Display project backlog
     */
    public function index($username, $project)
    {
        return view('tenant.manage.projects.backlog', compact('username', 'project'));
    }
}