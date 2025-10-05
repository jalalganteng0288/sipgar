<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index()
    {
        // 1. DATA UNTUK GRAFIK SEBARAN UNIT
        $unitsPerDistrict = HousingProject::with('houseTypes', 'district')
            ->whereHas('district', function ($query) {
                $query->where('city_code', 3205); // Filter Kabupaten Garut
            })
            ->get()
            ->groupBy('district.name')
            ->map(function ($projectsInDistrict) {
                return $projectsInDistrict->sum(function ($project) {
                    return $project->houseTypes->sum('units_available');
                });
            })
            ->filter(function ($totalUnits) {
                return $totalUnits > 0; // Hanya tampilkan kecamatan yang punya unit
            })
            ->sortDesc();

        // 2. DATA UNTUK GRAFIK RATA-RATA HARGA
        $avgPricePerDistrict = HousingProject::with('houseTypes', 'district')
            ->whereHas('district', function ($query) {
                $query->where('city_code', 3205);
            })
            ->get()
            ->groupBy('district.name')
            ->map(function ($projectsInDistrict, $districtName) {
                $totalLandPricePerM2 = 0;
                $totalBuildingPricePerM2 = 0;
                $houseTypeCount = 0;

                foreach ($projectsInDistrict as $project) {
                    foreach ($project->houseTypes as $houseType) {
                        if ($houseType->land_area > 0 && $houseType->building_area > 0 && $houseType->price > 0) {
                            $totalLandPricePerM2 += $houseType->price / $houseType->land_area;
                            $totalBuildingPricePerM2 += $houseType->price / $houseType->building_area;
                            $houseTypeCount++;
                        }
                    }
                }

                if ($houseTypeCount === 0) {
                    return null; // Kembalikan null jika tidak ada data valid
                }

                return [
                    'district_name' => $districtName,
                    'avg_price_per_land' => $totalLandPricePerM2 / $houseTypeCount,
                    'avg_price_per_building' => $totalBuildingPricePerM2 / $houseTypeCount,
                ];
            })
            ->filter() // Hapus entri yang null
            ->values();

        return view('charts.index', [
            'unitsPerDistrict' => $unitsPerDistrict,
            'avgPricePerDistrict' => $avgPricePerDistrict,
        ]);
    }
}