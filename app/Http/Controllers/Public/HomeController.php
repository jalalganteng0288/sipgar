<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\HousingProject;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Bagian filter pencarian Anda (tidak perlu diubah)
        $query = HousingProject::with('houseTypes', 'district', 'village');
        if ($request->filled('district_code')) {
            $query->where('district_code', $request->district_code);
        }
        if ($request->filled('village_code')) {
            $query->where('village_code', $request->village_code);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('developer_name')) {
            $query->where('developer_name', 'like', '%' . $request->developer_name . '%');
        }
        $projects = $query->latest()->paginate(10);
        $districts = District::where('city_code', 3205)->get();

        // ===============================================================
        // AWAL DARI LOGIKA STATISTIK FINAL
        // ===============================================================

        // 1. Statistik utama (ini sudah bekerja dengan baik)
        $stats = [
            'total_developers' => HousingProject::distinct('developer_name')->count(),
            'total_locations' => HousingProject::count(),
            'total_units' => HouseType::sum('total_units'),
        ];

        // 2. Siapkan kerangka untuk statistik detail dengan nilai awal 0
        $stats['komersil'] = [
            'kavling' => 0,
            'pembangunan' => 0,
            'ready_stock' => 0,
            'dipesan' => 0,
            'terjual' => 0,
            'antrian' => 0
        ];
        $stats['subsidi'] = [
            'kavling' => 0,
            'pembangunan' => 0,
            'ready_stock' => 0,
            'proses_bank' => 0,
            'terjual' => 0,
            'antrian' => 0
        ];

        // 3. Ambil semua data agregat dalam satu query efisien
        $unitStats = HouseType::join('housing_projects', 'house_types.housing_project_id', '=', 'housing_projects.id')
            ->select('housing_projects.type', 'house_types.status', DB::raw('SUM(house_types.total_units) as total'))
            ->groupBy('housing_projects.type', 'house_types.status')
            ->get();

        // 4. Proses hasil query dengan logika pemetaan yang fleksibel
        foreach ($unitStats as $stat) {
            $type = strtolower($stat->type); // 'komersil' atau 'subsidi'
            $rawStatus = strtolower($stat->status); // status mentah dari DB, mis: 'ready stock'
            $targetKey = null; // Kunci target di array $stats

            // Logika pemetaan: Mencocokkan berbagai kemungkinan nama status ke satu kunci target
            switch ($rawStatus) {
                case 'kavling':
                    $targetKey = 'kavling';
                    break;
                case 'pembangunan':
                    $targetKey = 'pembangunan';
                    break;
                case 'ready stock':
                case 'tersedia': // <-- Ini akan menangani data lama Anda
                    $targetKey = 'ready_stock';
                    break;
                case 'dipesan':
                case 'booking': // <-- Ini juga menangani variasi
                    $targetKey = 'dipesan';
                    break;
                case 'proses bank':
                    $targetKey = 'proses_bank';
                    break;
                case 'terjual':
                    $targetKey = 'terjual';
                    break;
            }

            // Jika ada kunci target yang cocok, tambahkan jumlahnya
            if ($targetKey && isset($stats[$type][$targetKey])) {
                $stats[$type][$targetKey] += (int)$stat->total;
            }
        }

        // 5. Hitung total antrian berdasarkan hasil yang sudah dipetakan
        $stats['komersil']['antrian'] = $stats['komersil']['kavling'] + $stats['komersil']['pembangunan'] + $stats['komersil']['ready_stock'] + $stats['komersil']['dipesan'] + $stats['komersil']['terjual'];
        $stats['subsidi']['antrian'] = $stats['subsidi']['kavling'] + $stats['subsidi']['pembangunan'] + $stats['subsidi']['ready_stock'] + $stats['subsidi']['proses_bank'] + $stats['subsidi']['terjual'];

        // ===============================================================
        // AKHIR DARI LOGIKA STATISTIK FINAL
        // ===============================================================

        return view('welcome', compact('projects', 'districts', 'stats'));
    }

    public function show(HousingProject $project)
    {
        // Memuat relasi yang diperlukan untuk ditampilkan di view
        $project->load('houseTypes', 'district', 'village', 'images');

        // Mengarahkan ke view 'projects.show' dengan data proyek
        return view('projects.show', compact('project'));
    }
}
