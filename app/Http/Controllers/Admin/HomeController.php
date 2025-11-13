<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\HouseType;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // ==== 1. Ambil statistik utama ====
        $totalProjects = HousingProject::count();
        $totalDevelopers = HousingProject::distinct('developer_name')->count('developer_name');
        $totalUnits = HouseType::sum('total_units');
        $totalUnitsSold = HouseType::where('status', 'terjual')->sum('total_units');

        // ==== 2. Buat data GeoJSON untuk peta ====
        $projects = HousingProject::select('id', 'name', 'status', 'latitude', 'longitude')->get();

        $geoJsonData = [];

        foreach ($projects as $project) {
            if ($project->latitude && $project->longitude) {
                $geoJsonData[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (float) $project->longitude,
                            (float) $project->latitude,
                        ],
                    ],
                    'properties' => [
                        'name' => $project->name,
                        'status' => $project->status,
                        'url' => route('admin.projects.show', $project->id),
                    ],
                ];
            }
        }

        // ==== 3. Kirim data ke view ====
        return view('dashboard', [
            'totalProjects' => $totalProjects,
            'totalDevelopers' => $totalDevelopers,
            'totalUnits' => $totalUnits,
            'totalUnitsSold' => $totalUnitsSold,
            'geoJsonData' => $geoJsonData,
        ]);
    }
}
