<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id)
    {
        $projects = json_decode(file_get_contents(storage_path('data/projects.json')), true);
        $project = array_filter($projects, function ($project) use ($id) {
            return $project['id'] == $id;
        })[$id - 1];
        return view('project-details', compact('project'));
    }
}
