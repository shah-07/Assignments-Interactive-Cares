<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkExpController extends Controller
{
    public function index()
    {
        $pageTitle = "Work Experience";
        $workExperiences = json_decode(file_get_contents(storage_path('data/work-experiences.json')), true);
        return view('work experiences.experiences', compact('pageTitle', 'workExperiences'));
    }
}
