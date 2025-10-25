<?php

namespace App\Http\Controllers\Tenant\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display project kanban board
     */
    public function show($username, $project)
    {
        return view('tenant.manage.projects.board', compact('username', 'project'));
    }
}