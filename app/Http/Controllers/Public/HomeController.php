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

        // ... (Logika filter pencarian Anda tetap di sini) ...
        // Filter berdasarkan NAMA PERUMAHAN
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        // Filter berdasarkan KECAMATAN
        if ($request->filled('district')) {
            $query->where('district_code', $request->district);
        }
        // Filter berdasarkan DESA
        if ($request->filled('village')) {
            $query->where('village_code', $request->village);
        }
        // Filter berdasarkan RENTANG HARGA
        if ($request->filled('price_min')) {
            $query->whereHas('houseTypes', function ($q) use ($request) {
                $q->where('price', '>=', $request->price_min);
            });
        }
        if ($request->filled('price_max')) {
            $query->whereHas('houseTypes', function ($q) use ($request) {
                $q->where('price', '<=', $request->price_max);
            });
        }
        // Filter berdasarkan ID LOKASI
        if ($request->filled('project_id')) {
            $query->where('id', $request->project_id);
        }
        // Filter berdasarkan ID RUMAH
        if ($request->filled('house_type_id')) {
            $query->whereHas('houseTypes', function ($q) use ($request) {
                $q->where('id', $request->house_type_id);
            });
        }

        $projects = $query->latest()->paginate(9)->withQueryString();

        // +++ KODE BARU UNTUK STATISTIK LENGKAP +++
        $allHouseTypes = HouseType::with('housingProject')->get();
        $stats = [
            'total_developers' => HousingProject::distinct('developer_name')->count(),
            'total_locations' => HousingProject::count(),
            'total_units' => $allHouseTypes->sum('total_units'),
            'komersil' => [
                'kavling' => $allHouseTypes->where('housingProject.type', 'Komersil')->where('status', 'Kavling')->sum('total_units'),
                'pembangunan' => $allHouseTypes->where('housingProject.type', 'Komersil')->where('status', 'Pembangunan')->sum('total_units'),
                'ready_stock' => $allHouseTypes->where('housingProject.type', 'Komersil')->where('status', 'Ready Stock')->sum('total_units'),
                'dipesan' => $allHouseTypes->where('housingProject.type', 'Komersil')->where('status', 'Dipesan')->sum('total_units'),
                'terjual' => $allHouseTypes->where('housingProject.type', 'Komersil')->where('status', 'Terjual')->sum('total_units'),
            ],
            'subsidi' => [
                'kavling' => $allHouseTypes->where('housingProject.type', 'Subsidi')->where('status', 'Kavling')->sum('total_units'),
                'pembangunan' => $allHouseTypes->where('housingProject.type', 'Subsidi')->where('status', 'Pembangunan')->sum('total_units'),
                'ready_stock' => $allHouseTypes->where('housingProject.type', 'Subsidi')->where('status', 'Ready Stock')->sum('total_units'),
                'proses_bank' => $allHouseTypes->where('housingProject.type', 'Subsidi')->where('status', 'Proses Bank')->sum('total_units'),
                'terjual' => $allHouseTypes->where('housingProject.type', 'Subsidi')->where('status', 'Terjual')->sum('total_units'),
            ],
        ];
        // Hitung total antrian untuk Komersil dan Subsidi
        $stats['komersil']['antrian'] = array_sum($stats['komersil']);
        $stats['subsidi']['antrian'] = array_sum($stats['subsidi']);


        return view('welcome', compact('projects', 'districts', 'stats'));
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
