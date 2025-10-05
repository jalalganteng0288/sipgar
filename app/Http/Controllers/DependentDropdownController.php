<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Village; // <--- Import model Village

class DependentDropdownController extends Controller
{
    public function villages(Request $request)
    {
        // Validasi input untuk memastikan district_code ada
        $request->validate([
            'district_code' => 'required|exists:indonesia_districts,code',
        ]);

        // Ambil data desa berdasarkan district_code
        $villages = Village::where('district_code', $request->district_code)
            ->pluck('name', 'code');

        // Kembalikan data dalam format JSON
        return response()->json($villages);
    }
}