<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada

class HouseTypeController extends Controller
{
    public function create(HousingProject $project)
    {
        return view('admin.house-types.create', compact('project'));
    }

    // --- GANTI METHOD STORE INI ---
    public function store(Request $request, HousingProject $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'total_units' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
            'floor_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi denah
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('house-type-images', 'public');
        }
        if ($request->hasFile('floor_plan')) {
            $validated['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        }

        $validated['housing_project_id'] = $project->id;
        HouseType::create($validated);

        return redirect()->route('admin.projects.show', $project->id)->with('success', 'Tipe rumah berhasil ditambahkan.');
    }

    public function edit(HouseType $houseType)
    {
        return view('admin.house-types.edit', compact('houseType'));
    }

    // --- GANTI METHOD UPDATE INI ---
    public function update(Request $request, HouseType $houseType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'total_units' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'floor_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($houseType->image) {
                Storage::disk('public')->delete($houseType->image);
            }
            $validated['image'] = $request->file('image')->store('house-type-images', 'public');
        }

        if ($request->hasFile('floor_plan')) {
            if ($houseType->floor_plan) {
                Storage::disk('public')->delete($houseType->floor_plan);
            }
            $validated['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        }

        $houseType->update($validated);

        return redirect()->route('admin.projects.show', $houseType->housing_project_id)->with('success', 'Tipe rumah berhasil diperbarui.');
    }

    public function destroy(HouseType $houseType)
    {
        $projectId = $houseType->housing_project_id;
        
        // Hapus gambar dari storage sebelum hapus data
        if ($houseType->image) {
            Storage::disk('public')->delete($houseType->image);
        }
        if ($houseType->floor_plan) {
            Storage::disk('public')->delete($houseType->floor_plan);
        }

        $houseType->delete();

        return redirect()->route('admin.projects.show', $projectId)->with('success', 'Tipe rumah berhasil dihapus.');
    }
}