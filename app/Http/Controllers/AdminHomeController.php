<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\HouseType; // <-- Tambahkan import HouseType
use App\Models\Indonesia\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // <-- Tambahkan import Cache

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $typeFilter = $request->input('type');
        $districts = District::where('city_code', 3205)->get();
        $districtNames = $districts->pluck('name');
        
        // 🚨 PERBAIKAN 1: Ambil daftar tipe rumah unik untuk filter (menggunakan cache)
        $houseTypes = Cache::remember('all_house_types', 60*60, function () {
            // Mengambil semua nama tipe yang unik dan tidak kosong
            return HouseType::select('name')
                ->distinct()
                ->pluck('name')
                ->filter() // Menghilangkan nilai kosong/null
                ->values()
                ->toArray();
        });


        // ... (Logika kalkulasi grafik Anda - TIDAK ADA PERUBAHAN) ...
        $unitsPerDistrict = [];
        foreach ($districts as $district) {
            $query = HousingProject::where('district_code', $district->code);
            if ($typeFilter) {
                // Catatan: Jika kolom filter di HousingProject bernama 'type', ini benar. 
                // Jika filter ada di HouseType, logic join Anda sudah menangani sum di bawah.
                // Untuk amannya, kita asumsikan filter ada di HouseType.
                // $query->where('type', $typeFilter); 
            }
            // Logic ini sudah benar jika HouseType memiliki kolom 'type'
            $totalUnits = $query->withSum(['houseTypes' => function ($query) use ($typeFilter) {
                if ($typeFilter) {
                    $query->where('name', $typeFilter); // Filter di HouseType berdasarkan nama/tipe
                }
            }], 'total_units')->get()->sum('house_types_sum_total_units');

            $unitsPerDistrict[] = $totalUnits;
        }
        
        $avgPricePerDistrict = [];
        foreach ($districts as $district) {
            $query = HousingProject::where('district_code', $district->code)
                ->join('house_types', 'housing_projects.id', '=', 'house_types.housing_project_id');
                
            if ($typeFilter) {
                $query->where('house_types.name', $typeFilter); // Filter harga di HouseType
            }
            $avgPrice = $query->avg('house_types.price');
            $avgPricePerDistrict[] = $avgPrice ?? 0;
        }

        // 🚨 PERBAIKAN 2: Tambahkan variabel $houseTypes ke view
        return view('charts.public', [
            'districtNames' => $districtNames,
            'unitsPerDistrict' => $unitsPerDistrict,
            'avgPricePerDistrict' => $avgPricePerDistrict,
            'currentFilter' => $typeFilter,
            'houseTypes' => $houseTypes, // <-- BARU: Variabel yang hilang
        ]);
    }
}