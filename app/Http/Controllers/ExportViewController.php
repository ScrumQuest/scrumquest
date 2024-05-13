<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportViewController extends Controller
{
    public function index(Request $request, Project $project) {
        if(!$request->user()->is_admin) {
            abort(403);
        }

        $exports = Storage::disk('local')->files("sprintviews/$project->id/");
        $exports = array_map(function ($export) {
            $lastDirSeparator = strrpos($export, '/');
            $export = str_replace('.html', '', $export);
            return substr($export, $lastDirSeparator + 1);
        }, $exports);

        return view('sprintexport.index', [
            'project' => $project,
            'exports' => $exports
        ]);
    }

    public function show(Request $request, Project $project, string $export) {
        if(!$request->user()->is_admin) {
            abort(403);
        }

        $htmlView = Storage::disk('local')->get("sprintviews/$project->id/$export.html");

        return response($htmlView, 200);
    }
}
