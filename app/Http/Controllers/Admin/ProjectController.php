<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;

use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\StoreProjectRequest;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listProject = Project::all();
        return view('admin.projects.index', compact('listProject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        $categories = Category::all();
        return view("admin.projects.create", compact('project', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {

        $data = $request->validated();
        $project = new Project();
        $project->fill($data);
        $project->slug = Str::slug($request->title);
        $project->save();

        return redirect()->route("admin.projects.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // dd($example);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $categories = Category::all();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validatedData = $request->validated();

        // modifica dello Slug automatica in base alla modifica del titolo
        $validatedData['slug'] = Str::slug($validatedData['title']);

        $project->update($validatedData);

        // return redirect()->route('admin.projects.show');
        return redirect()->route('admin.projects.show', ['project' => $project->slug])->with('message', 'Il post ' . $project->title . ' è stato modificato');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        // return redirect()->route('admin.projects.index');
        return redirect()->route('admin.projects.index')->with('message', 'Il post ' . $project->title . ' è stato cancellato');
    }
}
