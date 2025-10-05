<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\District;

class ChartController extends Controller
{
    public function index()
    {
        // 1. DATA UNTUK GRAFIK SEBARAN UNIT
        $unitsPerDistrict = HouseType::join('housing_projects', 'house_types.housing_project_id', '=', 'housing_projects.id')
            ->join('districts', 'housing_projects.district_code', '=', 'districts.code')
            ->select('districts.name as district_name', DB::raw('SUM(house_types.units_available) as total_units'))
            ->where('districts.city_code', 3205) // Filter hanya untuk Kabupaten Garut
            ->groupBy('districts.name')
            ->orderBy('total_units', 'desc')
            ->pluck('total_units', 'district_name');

        // 2. DATA UNTUK GRAFIK RATA-RATA HARGA
        $avgPricePerDistrict = HouseType::join('housing_projects', 'house_types.housing_project_id', '=', 'housing_projects.id')
            ->join('districts', 'housing_projects.district_code', '=', 'districts.code')
            ->select(
                'districts.name as district_name',
                DB::raw('AVG(house_types.price / house_types.land_area) as avg_price_per_land'),
                DB::raw('AVG(house_types.price / house_types.building_area) as avg_price_per_building')
            )
            ->where('districts.city_code', 3205)
            ->where('house_types.land_area', '>', 0) // Hindari pembagian dengan nol
            ->where('house_types.building_area', '>', 0) // Hindari pembagian dengan nol
            ->groupBy('districts.name')
            ->orderBy('district_name', 'asc')
            ->get();
            
        return view('charts.index', [
            'unitsPerDistrict' => $unitsPerDistrict,
            'avgPricePerDistrict' => $avgPricePerDistrict,
        ]);
    }
}