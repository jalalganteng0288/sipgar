<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Village; // <--- Import model Village

class DependentDropdownController extends Controller
{
    public function villages(string $district_code)
    {
        // Validasi bisa langsung dari tipe data parameter
        $villages = Village::where('district_code', $district_code)
            ->pluck('name', 'code');

        return response()->json($villages);
    }
}
