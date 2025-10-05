<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\District;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kecamatan di Kabupaten Garut (kode: 3205)
        $districts = District::where('city_code', 3205)->get();

        $query = HousingProject::with('district', 'houseTypes'); // Eager loading untuk performa

        // Filter berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kecamatan
        if ($request->filled('district')) {
            $query->where('district_code', $request->district);
        }

        // Filter berdasarkan rentang harga pada Tipe Rumah
        if ($request->filled('price_range')) {
            $range = explode('-', $request->price_range);
            $query->whereHas('houseTypes', function ($q) use ($range) {
                $q->whereBetween('price', [$range[0], $range[1]]);
            });
        }

        $projects = $query->latest()->paginate(9); // Ubah ke 9 agar pas dengan grid 3 kolom

        return view('welcome', compact('projects', 'districts'));
    }
    // app/Http/Controllers/Public/HomeController.php

    // ...
    public function show(HousingProject $project)
    {
        // Kita tambahkan ->load('houseTypes') untuk mengambil data relasinya
        $project->load('houseTypes');

        return view('projects.show', compact('project'));
    }

    public function getAllProjectsForMap()
    {
        $projects = \App\Models\HousingProject::select('id', 'name', 'latitude', 'longitude')->get();

        // Tambahkan URL detail untuk setiap project
        $projectsWithUrl = $projects->map(function ($project) {
            $project->url = route('projects.show', $project->id);
            return $project;
        });

        return response()->json($projectsWithUrl);
    }
    // ...
}
