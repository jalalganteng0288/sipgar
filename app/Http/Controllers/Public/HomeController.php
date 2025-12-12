<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject;
use App\Models\District; // Pastikan Model District sesuai namespace kamu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. MULAI QUERY UTAMA
        $query = HousingProject::with('houseTypes', 'district', 'village');

        // ===============================================================
        // PERBAIKAN LOGIKA PENCARIAN (SESUAI INPUT VIEW)
        // ===============================================================

        // A. Filter Kecamatan (Input name="district")
        // Controller menangkap 'district', lalu dicocokkan ke kolom 'district_code'
        if ($request->filled('district')) {
            $query->where('district_code', $request->district);
        }

        // B. Filter Desa (Input name="village")
        // Controller menangkap 'village', lalu dicocokkan ke kolom 'village_code'
        if ($request->filled('village')) {
            $query->where('village_code', $request->village);
        }

        // C. Pencarian Teks (Input name="search")
        // Mencari ke Nama Perumahan ATAU Nama Developer
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('developer_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // D. Filter Harga (Input name="price_min" & "price_max")
        // Menggunakan whereHas untuk mengecek harga di tabel relasi house_types
        if ($request->filled('price_min')) {
            $query->whereHas('houseTypes', function($q) use ($request) {
                $q->where('price', '>=', $request->price_min);
            });
        }
        if ($request->filled('price_max')) {
            $query->whereHas('houseTypes', function($q) use ($request) {
                $q->where('price', '<=', $request->price_max);
            });
        }

        // E. Filter ID Lokasi (Input name="project_id")
        if ($request->filled('project_id')) {
            $query->where('id', $request->project_id);
        }

        // F. Filter ID Tipe Rumah (Input name="house_type_id")
        if ($request->filled('house_type_id')) {
             $query->whereHas('houseTypes', function($q) use ($request) {
                $q->where('id', $request->house_type_id);
            });
        }

        // Eksekusi Query & Paginasi
        $projects = $query->latest()->paginate(9); 

        // Data Kecamatan untuk Dropdown (Khusus Kab. Garut code: 3205)
        $districts = District::where('city_code', 3205)->get();

        // ===============================================================
        // LOGIKA STATISTIK (DIBIARKAN SAMA KARENA SUDAH BAGUS)
        // ===============================================================
        $stats = [
            'total_developers' => HousingProject::distinct('developer_name')->count(),
            'total_locations' => HousingProject::count(),
            'total_units' => HouseType::sum('total_units'),
        ];

        $stats['komersil'] = [
            'kavling' => 0, 'pembangunan' => 0, 'ready_stock' => 0, 
            'dipesan' => 0, 'terjual' => 0, 'antrian' => 0
        ];
        $stats['subsidi'] = [
            'kavling' => 0, 'pembangunan' => 0, 'ready_stock' => 0, 
            'proses_bank' => 0, 'terjual' => 0, 'antrian' => 0
        ];

        $unitStats = HouseType::join('housing_projects', 'house_types.housing_project_id', '=', 'housing_projects.id')
            ->select('housing_projects.type', 'house_types.status', DB::raw('SUM(house_types.total_units) as total'))
            ->groupBy('housing_projects.type', 'house_types.status')
            ->get();

        foreach ($unitStats as $stat) {
            $type = strtolower($stat->type);
            $rawStatus = strtolower($stat->status);
            $targetKey = null;

            switch ($rawStatus) {
                case 'kavling': $targetKey = 'kavling'; break;
                case 'pembangunan': $targetKey = 'pembangunan'; break;
                case 'ready stock': case 'tersedia': $targetKey = 'ready_stock'; break;
                case 'dipesan': case 'booking': $targetKey = 'dipesan'; break;
                case 'proses bank': $targetKey = 'proses_bank'; break;
                case 'terjual': $targetKey = 'terjual'; break;
            }

            if ($targetKey && isset($stats[$type][$targetKey])) {
                $stats[$type][$targetKey] += (int)$stat->total;
            }
        }

        // Hitung total antrian
        $stats['komersil']['antrian'] = array_sum($stats['komersil']) - $stats['komersil']['antrian'];
        $stats['subsidi']['antrian'] = array_sum($stats['subsidi']) - $stats['subsidi']['antrian'];

        return view('welcome', compact('projects', 'districts', 'stats'));
    }

    public function show(HousingProject $project)
    {
        $project->load('houseTypes', 'district', 'village', 'images');
        return view('projects.show', compact('project'));
    }
}