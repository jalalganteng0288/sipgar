<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class HouseTypeController extends Controller
{
    /**
     * Tampilkan form untuk membuat tipe rumah baru pada proyek tertentu.
     */
    public function create(HousingProject $project)
    {
        // 1. OTORISASI: Cek apakah user yang login berhak mengelola (mengupdate) proyek ini.
        // Otorisasi ini akan menggunakan 'update' method dari HousingProjectPolicy.
        $this->authorize('update', $project);

        return view('admin.house-types.create', compact('project'));
    }

    /**
     * Simpan tipe rumah baru untuk proyek tertentu.
     */
    public function store(Request $request, HousingProject $project)
    {
        // 1. OTORISASI: Cek kembali sebelum menyimpan.
        $this->authorize('update', $project);

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
            $validated['image'] = $request->file('image')->store('house-type-images', 'public');
        }
        if ($request->hasFile('floor_plan')) {
            $validated['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        }

        // Simpan tipe rumah di bawah proyek ini
        $project->houseTypes()->create($validated);

        return redirect()->route('admin.projects.show', $project->id)->with('success', 'Tipe rumah berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit tipe rumah.
     * (Asumsi HouseType model memiliki relasi ke HousingProject)
     */
    public function edit(HouseType $houseType)
    {
        // 1. OTORISASI: Cek apakah user yang login berhak mengedit Tipe Rumah ini.
        // Kita cek kepemilikan melalui Proyek Induknya ($houseType->housingProject).
        $this->authorize('update', $houseType->housingProject); 

        return view('admin.house-types.edit', compact('houseType'));
    }

    /**
     * Update data tipe rumah di database.
     */
    public function update(Request $request, HouseType $houseType)
    {
        // 1. OTORISASI: Cek kembali sebelum mengupdate.
        $this->authorize('update', $houseType->housingProject);
        
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

        // ... (Logika upload dan hapus file lama)

        if ($request->hasFile('image')) {
            if ($houseType->image) {
                Storage::disk('public')->delete($houseType->image);
            }
            $validated['image'] = $request->file('image')->store('house-type-images', 'public');
        } else {
            // Penting: Jika user tidak upload, jangan hapus data image lama dari array $validated
            unset($validated['image']); 
        }

        if ($request->hasFile('floor_plan')) {
            if ($houseType->floor_plan) {
                Storage::disk('public')->delete($houseType->floor_plan);
            }
            $validated['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        } else {
            unset($validated['floor_plan']);
        }
        
        $houseType->update($validated);

        return redirect()->route('admin.projects.show', $houseType->housing_project_id)->with('success', 'Tipe rumah berhasil diperbarui.');
    }

    /**
     * Hapus tipe rumah dari database.
     */
    public function destroy(HouseType $houseType)
    {
        // 1. OTORISASI: Cek sebelum menghapus.
        $this->authorize('update', $houseType->housingProject);
        
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