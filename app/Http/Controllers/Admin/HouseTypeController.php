<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\HouseType;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{
    // Method untuk menampilkan form tambah tipe rumah
    public function create(Request $request)
    {
        $project_id = $request->query('project_id');
        return view('admin.house-types.create', compact('project_id'));
    }

    // Method untuk menyimpan tipe rumah baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'housing_project_id' => 'required|exists:housing_projects,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        HouseType::create($request->all());

        return redirect()->route('admin.projects.show', $request->housing_project_id)
            ->with('success', 'Tipe rumah berhasil ditambahkan.');
    }

    // Method untuk menampilkan form edit tipe rumah
    public function edit(HouseType $houseType)
    {
        return view('admin.house-types.edit', compact('houseType'));
    }

    // Method untuk update data tipe rumah di database
    public function update(Request $request, HouseType $houseType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'land_area' => 'required|numeric',
            'building_area' => 'required|numeric',
            'units_available' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'floor_plan' => 'nullable|image|max:2048',
            'specifications' => 'nullable|json',
        ]);

        $data = $request->except(['_token', '_method']);

        if ($request->hasFile('image')) {
            if ($houseType->image) Storage::disk('public')->delete($houseType->image);
            $data['image'] = $request->file('image')->store('house-types', 'public');
        }

        if ($request->hasFile('floor_plan')) {
            if ($houseType->floor_plan) Storage::disk('public')->delete($houseType->floor_plan);
            $data['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        }

        $houseType->update($data);

        return redirect()->route('admin.projects.show', $houseType->housing_project_id)
            ->with('success', 'Tipe rumah berhasil diperbarui.');
    }


    // Method untuk menghapus tipe rumah dari database
    public function destroy(HouseType $houseType)
    {
        $projectId = $houseType->housing_project_id;
        $houseType->delete();

        return redirect()->route('admin.projects.show', $projectId)
            ->with('success', 'Tipe rumah berhasil dihapus.');
    }
}
