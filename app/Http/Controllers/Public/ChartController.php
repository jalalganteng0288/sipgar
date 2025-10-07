<?php

// ===============================================================
// PERBAIKAN DI SINI
// ===============================================================
// Ganti baris lama yang salah
// namespace App\Http-Controllers\Public;

// Menjadi baris baru yang benar
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\Indonesia\District;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $typeFilter = $request->input('type');
        $districts = District::where('city_code', 3205)->get();
        $districtNames = $districts->pluck('name');

        // ... (Logika kalkulasi grafik Anda tidak perlu diubah) ...
        $unitsPerDistrict = [];
        foreach ($districts as $district) {
            $query = HousingProject::where('district_code', $district->code);
            if ($typeFilter) {
                $query->where('type', $typeFilter);
            }
            $totalUnits = $query->withSum('houseTypes', 'total_units')->get()->sum('house_types_sum_total_units');
            $unitsPerDistrict[] = $totalUnits;
        }
        
        $avgPricePerDistrict = [];
        foreach ($districts as $district) {
            $query = HousingProject::where('district_code', $district->code)
                ->join('house_types', 'housing_projects.id', '=', 'house_types.housing_project_id');
            if ($typeFilter) {
                $query->where('housing_projects.type', $typeFilter);
            }
            $avgPrice = $query->avg('house_types.price');
            $avgPricePerDistrict[] = $avgPrice ?? 0;
        }

        // Arahkan ke view publik yang baru
        return view('charts.public', [
            'districtNames' => $districtNames,
            'unitsPerDistrict' => $unitsPerDistrict,
            'avgPricePerDistrict' => $avgPricePerDistrict,
            'currentFilter' => $typeFilter,
        ]);
    }
}