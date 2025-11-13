<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject; // Pastikan ini sudah ada
use Illuminate\Http\Request;
// Jika Anda menggunakan Model Developer untuk hal lain, import di sini:
// use App\Models\Developer; 

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data statistik (Tidak ada perubahan signifikan di sini)
        $totalProjects = HousingProject::count();
        $totalDevelopers = HousingProject::distinct('developer_name')->count();
        $totalUnits = HouseType::sum('total_units');
        $totalUnitsSold = HouseType::where('status', 'Terjual')->sum('total_units');

        // 2. Logika Peta GIS (PERBAIKAN KRITIS DI SINI)
        $projects = HousingProject::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // 🚨 PERBAIKAN: WAJIB ditambahkan ->toArray() di akhir
        $geoJsonData = $projects->map(function ($project) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    // Pastikan urutan [longitude, latitude] untuk GeoJSON
                    'coordinates' => [(float)$project->longitude, (float)$project->latitude] 
                ],
                'properties' => [
                    'name' => $project->name,
                    'status' => $project->status,
                    // Memanggil rute yang sudah kita perbaiki di routes/web.php
                    'url' => route('projects.show', $project->id) 
                ]
            ];
        })->toArray(); // <-- SOLUSI KRITIS: Mengubah Collection menjadi Array

        // 3. Kirim data ke view
        return view('dashboard', compact(
            'totalProjects',
            'totalDevelopers',
            'totalUnits',
            'totalUnitsSold',
            'geoJsonData'
        ));
    }
}