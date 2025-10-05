<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Village; // <--- Import model Village

class DependentDropdownController extends Controller
{
    public function villages(Request $request)
    {
        // Ganti 'indonesia_districts' menjadi 'districts'
        $request->validate([
            'district_code' => 'required|exists:districts,code',
        ]);

        $villages = Village::where('district_code', $request->district_code)
            ->pluck('name', 'code');

        return response()->json($villages);
    }
}
