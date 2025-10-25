<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display all issues list
     */
    public function index($username, $project)
    {
        return view('tenant.manage.projects.issues.index', compact('username', 'project'));
    }

    /**
     * Display single issue detail
     */
    public function show($username, $project, $issue)
    {
        return view('tenant.manage.projects.issues.show', compact('username', 'project', 'issue'));
    }
}