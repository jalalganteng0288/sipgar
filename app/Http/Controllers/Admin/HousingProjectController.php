<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class HousingProjectController extends Controller
{
    public function index()
    {
        // Perbaikan pagination
        $projects = HousingProject::latest()->paginate(10);
        return view('admin.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        // Perbaikan dropdown create
        $districts = District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
        return view('admin.projects.create', compact('districts'));
    }

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

    public function show(HousingProject $project)
    {
        $project->load('houseTypes');
        return view('admin.projects.show', ['project' => $project]);
    }

    public function edit(HousingProject $project)
    {
        // Perbaikan dropdown edit
        $districts = District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
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
        // KODE PERBAIKAN UTAMA ADA DI SINI
        // ===============================================================

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
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
        ]);

        // Proses upload gambar utama JIKA ADA file baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            // Simpan path gambar baru ke dalam array data
            $validatedData['image'] = $request->file('image')->store('project-images', 'public');
        }

        // Proses upload siteplan JIKA ADA file baru
        if ($request->hasFile('site_plan')) {
            // Hapus siteplan lama jika ada
            if ($project->site_plan) {
                Storage::disk('public')->delete($project->site_plan);
            }
            // Simpan path siteplan baru ke dalam array data
            $validatedData['site_plan'] = $request->file('site_plan')->store('site-plans', 'public');
        }

        // Gunakan method update() dari model dengan data yang sudah divalidasi
        $project->update($validatedData);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }


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