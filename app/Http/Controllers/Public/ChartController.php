<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\Indonesia\District; // Menggunakan namespace yang benar sesuai file kamu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $typeFilter = $request->input('type');

        // 1. Ambil semua kode kecamatan yang unik dari tabel housing_projects
        // Ini memastikan hanya kecamatan yang ada perumahannya yang muncul
        $activeDistrictCodes = HousingProject::distinct()->pluck('district_code');

        // 2. Ambil data kecamatan berdasarkan kode tersebut
        $districts = District::whereIn('code', $activeDistrictCodes)->get();

        $districtNames = [];
        $unitsPerDistrict = [];
        $avgPricePerDistrict = [];

        foreach ($districts as $district) {
            // Query untuk menghitung unit
            $query = HousingProject::where('district_code', $district->code);
            
            if ($typeFilter) {
                $query->where('type', $typeFilter);
            }

            // Hitung total unit melalui relasi houseTypes
            $totalUnits = $query->withSum('houseTypes', 'total_units')
                               ->get()
                               ->sum('house_types_sum_total_units');

            // Hitung rata-rata harga
            $avgPrice = HousingProject::where('district_code', $district->code)
                ->join('house_types', 'housing_projects.id', '=', 'house_types.housing_project_id')
                ->when($typeFilter, function($q) use ($typeFilter) {
                    return $q->where('housing_projects.type', $typeFilter);
                })
                ->avg('house_types.price');

            // Masukkan ke array jika ada datanya
            if ($totalUnits > 0) {
                $districtNames[] = $district->name;
                $unitsPerDistrict[] = $totalUnits;
                $avgPricePerDistrict[] = round($avgPrice ?? 0);
            }
        }

        return view('charts.public', [
            'districtNames' => $districtNames,
            'unitsPerDistrict' => $unitsPerDistrict,
            'avgPricePerDistrict' => $avgPricePerDistrict,
            'currentFilter' => $typeFilter,
        ]);
    }
}