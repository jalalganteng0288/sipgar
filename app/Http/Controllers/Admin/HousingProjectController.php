<?php

namespace App\Http\Controllers\Admin;

use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HousingProject;

class HousingProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = HousingProject::all();
        return view('admin.projects.index', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kode 3205 adalah kode untuk Kabupaten Garut
        $districts = District::where('city_code', 3205)->pluck('name', 'code');

        return view('admin.projects.create', ['districts' => $districts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // --- VALIDASI DIPERBARUI ---
        $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code', // <-- Diperbarui
            'village_code' => 'required|exists:villages,code',   // <-- Diperbarui
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        HousingProject::create($request->all());

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HousingProject $project)
    {
        return view('admin.projects.show', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HousingProject $project)
    {
        $districts = District::where('city_code', 3205)->pluck('name', 'code');
        $villages = Village::where('district_code', $project->district_code)->pluck('name', 'code');

        return view('admin.projects.edit', [
            'project' => $project,
            'districts' => $districts,
            'villages' => $villages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HousingProject $project)
    {
        // --- VALIDASI DIPERBARUI ---
        $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code', // <-- Diperbarui
            'village_code' => 'required|exists:villages,code',   // <-- Diperbarui
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $project->update($request->all());

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HousingProject $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil dihapus.');
    }
}
