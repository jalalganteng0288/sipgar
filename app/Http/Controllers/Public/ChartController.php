<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\Indonesia\District;
use Illuminate\Http\Request; // Tambahkan ini

class ChartController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tipe dari request, default-nya null (semua)
        $typeFilter = $request->input('type');

        // Ambil semua kecamatan di Garut
        $districts = District::where('city_code', 3205)->get();
        $districtNames = $districts->pluck('name');

        // 1. Hitung total unit per kecamatan
        $unitsPerDistrict = [];
        foreach ($districts as $district) {
            $query = HousingProject::where('district_code', $district->code);

            // Terapkan filter jika ada
            if ($typeFilter) {
                $query->where('type', $typeFilter);
            }

            $totalUnits = $query->withSum('houseTypes', 'total_units')->get()->sum('house_types_sum_total_units');
            $unitsPerDistrict[] = $totalUnits;
        }
        
        // 2. Hitung rata-rata harga per kecamatan
        $avgPricePerDistrict = [];
        foreach ($districts as $district) {
            $query = HousingProject::where('district_code', $district->code)
                ->join('house_types', 'housing_projects.id', '=', 'house_types.housing_project_id');

            // Terapkan filter jika ada
            if ($typeFilter) {
                $query->where('housing_projects.type', $typeFilter);
            }

            $avgPrice = $query->avg('house_types.price');
            $avgPricePerDistrict[] = $avgPrice ?? 0;
        }

        return view('charts.index', [
            'districtNames' => $districtNames,
            'unitsPerDistrict' => $unitsPerDistrict,
            'avgPricePerDistrict' => $avgPricePerDistrict,
            'currentFilter' => $typeFilter, // Kirim filter saat ini ke view
        ]);
    }
}