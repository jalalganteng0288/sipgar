<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HouseUnit;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role; // Tambahkan ini jika diperlukan di masa depan

class ReportController extends Controller
{
    public function __construct()
    {
        // Memastikan hanya Admin yang dapat mengakses laporan ini
        $this->middleware('auth');
        $this->middleware(['role:admin']);
    }

    /**
     * Mengambil data unit perumahan secara komprehensif dan mengekspornya ke CSV.
     */
    public function exportUnitData()
    {
        // Ambil semua data Unit Rumah dengan relasi yang diperlukan (Eager Loading)
        $units = HouseUnit::with([
            'houseType.housingProject.developer',
            'houseType.housingProject.district',
            'houseType'
        ])
            ->get();

        // 1. Definisikan Header CSV untuk unduhan
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Laporan_Unit_Perumahan_SIPGAR_' . date('Ymd_His') . '.csv"',
        ];

        // Header Kolom Laporan
        $columns = [
            'ID_UNIT',
            'NAMA_PROYEK',
            'PENGEMBANG',
            'KONTAK_PENGEMBANG',
            'KECAMATAN',
            'TIPE_RUMAH',
            'LUAS_BANGUNAN_M2',
            'LUAS_TANAH_M2',
            'STATUS_UNIT',
            'HARGA_JUAL',
            'TANGGAL_DIBUAT',
        ];

        // 2. Callback untuk Mengolah Data menjadi CSV
        $callback = function () use ($units, $columns) {
            $file = fopen('php://output', 'w');

            // Tulis Header Kolom
            fputcsv($file, $columns);

            // Tulis Baris Data
            foreach ($units as $unit) {
                // Akses data melalui relasi
                $houseType = $unit->houseType;
                $project = $houseType->housingProject;
                $developer = $project->developer;

                // Mendefinisikan status unit agar mudah dibaca
                // Asumsi: status=1 adalah Terjual, status=0 adalah Tersedia (sesuai logika Dashboard sebelumnya)
                $status_display = ($unit->status == 1) ? 'TERJUAL' : 'TERSEDIA';

                $row = [
                    $unit->id,
                    $project->name ?? 'N/A',
                    $developer->company_name ?? 'N/A',
                    $developer->phone ?? '-',
                    $project->district->name ?? 'N/A',
                    $houseType->name ?? 'N/A',
                    $houseType->building_area ?? '',
                    $houseType->land_area ?? '',
                    $status_display,
                    (string)($houseType->selling_price ?? 0),
                    $unit->created_at->format('Y-m-d H:i:s'),
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        // 3. Kembalikan Response Unduhan
        return Response::stream($callback, 200, $headers);
    }
}
