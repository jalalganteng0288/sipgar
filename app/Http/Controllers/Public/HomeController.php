<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\HouseType; // <-- TAMBAHKAN INI
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\District;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $districts = District::where('city_code', 3205)->get();
        $query = HousingProject::with('district', 'houseTypes');

        // ==========================================================
        // LOGIKA FILTER ANDA YANG SUDAH ADA (TIDAK DIUBAH SAMA SEKALI)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('district') && $request->district != '') {
            $query->where('district_code', $request->district);
        }
        if ($request->has('price_range') && $request->price_range != '') {
            $range = explode('-', $request->price_range);
            $query->whereHas('houseTypes', function ($q) use ($range) {
                $q->whereBetween('price', [$range[0], $range[1]]);
            });
        }
        // ==========================================================

        $projects = $query->latest()->paginate(9);

        // +++ KODE BARU YANG DITAMBAHKAN UNTUK STATISTIK +++
        $stats = [
            'total_projects' => HousingProject::count(),
            'total_units' => HouseType::sum('units_available'),
            'total_locations' => HousingProject::distinct('village_code')->count(),
        ];

        return view('welcome', compact('projects', 'districts', 'stats')); // Tambahkan 'stats'
    }

    public function show(HousingProject $project)
    {
        $project->load('houseTypes', 'district.city.province', 'village', 'images');
        return view('projects.show', compact('project'));
    }

    // +++ METHOD BARU YANG DITAMBAHKAN UNTUK API PETA +++
    public function getAllProjectsForMap()
    {
        $projects = HousingProject::select('name', 'latitude', 'longitude', 'id')->get()->map(function ($project) {
            return [
                'name' => $project->name,
                'latitude' => $project->latitude,
                'longitude' => $project->longitude,
                'url' => route('projects.show', $project->id)
            ];
        });

        return response()->json($projects);
    }
}