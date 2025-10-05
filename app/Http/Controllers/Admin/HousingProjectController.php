<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

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
        return view('admin.projects.create', ['districts' => $districts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // --- VALIDASI DIGABUNGKAN DENGAN VALIDASI GAMBAR ---
        $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->except('image'); // Ambil semua data kecuali gambar

        // --- LOGIKA UNTUK MENYIMPAN GAMBAR ---
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        HousingProject::create($data); // Simpan data ke database

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
        // --- VALIDASI DIGABUNGKAN DENGAN VALIDASI GAMBAR ---
        $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->except('image');

        // --- LOGIKA UNTUK MEMPERBARUI GAMBAR ---
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            // Simpan gambar baru
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        $project->update($data); // Perbarui data di database

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HousingProject $project)
    {
        // --- LOGIKA UNTUK MENGHAPUS GAMBAR ---
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil dihapus.');
    }
}