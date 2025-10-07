<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Indonesia\District;
use App\Models\Indonesia\Village;

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
        $districts = District::where('city_code', 3205)->pluck('name', 'code');
        return view('admin.projects.create', compact('districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'type' => 'required|string|in:Komersil,Subsidi',
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'site_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('project-images', 'public');
        }
        if ($request->hasFile('site_plan')) {
            $data['site_plan'] = $request->file('site_plan')->store('site-plans', 'public');
        }

        HousingProject::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HousingProject $project)
    {
        $project->load('houseTypes');
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
        // ===============================================================
        // KODE PERBAIKAN ADA DI SINI
        // ===============================================================

        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'type' => 'required|string|in:Komersil,Subsidi',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'site_plan' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'district_code' => 'required',
            'village_code' => 'required',
        ]);

        // 2. Isi semua data teks ke proyek
        $project->fill($validatedData);

        // 3. Proses upload gambar utama JIKA ADA file baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            // Simpan gambar baru
            $project->image = $request->file('image')->store('project-images', 'public');
        }

        // 4. Proses upload siteplan JIKA ADA file baru
        if ($request->hasFile('site_plan')) {
            // Hapus siteplan lama jika ada
            if ($project->site_plan) {
                Storage::disk('public')->delete($project->site_plan);
            }
            // Simpan siteplan baru
            $project->site_plan = $request->file('site_plan')->store('site-plans', 'public');
        }

        // 5. Simpan semua perubahan ke database
        $project->save();

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HousingProject $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->site_plan) {
            Storage::disk('public')->delete($project->site_plan);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil dihapus.');
    }
}