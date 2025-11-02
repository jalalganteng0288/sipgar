<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject; // Pastikan ini sudah ada
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data statistik.
     */
    public function index()
    {
        // Ambil data statistik (Kode Asli)
        $totalProjects = HousingProject::count();
        $totalDevelopers = HousingProject::distinct('developer_name')->count();
        $totalUnits = HouseType::sum('total_units');
        $totalUnitsSold = HouseType::where('status', 'Terjual')->sum('total_units');

        // --- TAMBAHAN: Logika dari GisController ---
        // 1. Ambil data proyek yang punya koordinat.
        $projects = HousingProject::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // 2. Ubah data menjadi format GeoJSON
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
                    // Pastikan kamu punya route 'projects.show'
                    'url' => route('projects.show', $project->id) 
                ]
            ];
        });
        // --- AKHIR TAMBAHAN ---

        // Kirim data ke view (tambahkan $geoJsonData)
        return view('dashboard', compact(
            'totalProjects',
            'totalDevelopers',
            'totalUnits',
            'totalUnitsSold',
            'geoJsonData' // <-- Tambahkan variabel ini
        ));
    }
}