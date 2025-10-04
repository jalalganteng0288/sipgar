<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar kecamatan yang ada di tabel perumahan, berdasarkan relasi
        $districts = \Laravolt\Indonesia\Models\District::whereIn(
            'code',
            HousingProject::select('district_code')->whereNotNull('district_code')->distinct()
        )->orderBy('name')->pluck('name', 'code');

        $query = HousingProject::query();

        // Filter berdasarkan nama jika ada
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan district_code jika ada
        if ($request->filled('district')) {
            $query->where('district_code', $request->district);
        }

        $projects = $query->get();

        return view('welcome', [
            'projects' => $projects,
            'districts' => $districts,
        ]);
    }
}
