<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $projects = json_decode(file_get_contents(storage_path('data/projects.json')), true);
        $workExperiences = json_decode(file_get_contents(storage_path('data/work-experiences.json')), true);
        return view('home', compact('projects', 'workExperiences'));
    }
}
