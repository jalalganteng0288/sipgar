<?php

// app/Http/Controllers/Admin/GisController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;

class GisController extends Controller
{
    /**
     * Menampilkan halaman peta persebaran (GIS Dashboard).
     */
    public function index()
    {
        // 1. Ambil data proyek yang punya koordinat.
        // Cukup gunakan ->get(), JANGAN tambahkan ->select().
        $projects = HousingProject::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // 2. Ubah data (Collection) menjadi format GeoJSON (Array)
        // Ini semua diproses oleh PHP, BUKAN oleh SQL.
        $geoJsonData = $projects->map(function ($project) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$project->longitude, (float)$project->latitude]
                ],
                'properties' => [
                    'name' => $project->name,
                    'status' => $project->status,
                    // Kita gunakan nama rute 'project.show' (singular)
                    'url' => route('projects.show', $project->id)
                ]
            ];
        });

        // 3. Kirim data yang sudah diformat ke view
        return view('admin.gis.index', ['geoJsonData' => $geoJsonData]);
    }
}
