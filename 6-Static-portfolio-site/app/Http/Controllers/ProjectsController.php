<?php

namespace App\Http\Controllers;


class ProjectsController extends Controller
{
    public function index()
    {
        $pageTitle = "Projects";
        $projects = json_decode(file_get_contents(storage_path('data/projects.json')), true);
        return view('projects.projects', compact('pageTitle', 'projects'));
    }

    public function show($id)
    {
        $pageTitle = "Project Details";
        $projects = json_decode(file_get_contents(storage_path('data/projects.json')), true);
        $project = array_filter($projects, function ($project) use ($id) {
            return $project['id'] == $id;
        })[$id - 1];
        return view('projects.project-details', compact('pageTitle', 'project'));
    }
}
