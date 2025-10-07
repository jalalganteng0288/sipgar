<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HousingProject;
use App\Models\HouseType;
use App\Models\Indonesia\District;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportHousingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:housing-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import housing data from a CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data import...');

        // Path ke file CSV Anda
        $filePath = database_path('data/perumahan.csv');

        if (!file_exists($filePath)) {
            $this->error('File not found at: ' . $filePath);
            return 1;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Baca baris header untuk dilewati

        $count = 0;
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::beginTransaction();
            try {
                // Mapping kolom CSV ke variabel
                $namaPerumahan = trim($row[1]);
                $namaPengembang = trim($row[2]);
                $alamat = trim($row[3]);
                $tipeRumah = trim($row[4]);
                $kecamatanName = trim($row[6]);
                $koordinat = trim($row[11]);
                $jenis = trim($row[12]); // Subsidi atau Komersil

                // Cari district_code berdasarkan nama kecamatan
                $district = District::where('name', 'LIKE', '%' . $kecamatanName . '%')->first();
                if (!$district) {
                    $this->warn("Kecamatan '{$kecamatanName}' tidak ditemukan untuk perumahan '{$namaPerumahan}'. Melewati...");
                    DB::rollBack();
                    continue;
                }

                // Parsing koordinat
                $latitude = null;
                $longitude = null;
                if (!empty($koordinat)) {
                    $coords = explode(',', $koordinat);
                    if (count($coords) == 2) {
                        $latitude = trim($coords[0]);
                        $longitude = trim($coords[1]);
                    }
                }

                // Gunakan updateOrCreate untuk menghindari duplikasi data perumahan
                $project = HousingProject::updateOrCreate(
                    ['name' => $namaPerumahan],
                    [
                        'developer_name' => $namaPengembang,
                        'type' => !empty($jenis) ? $jenis : 'Komersil', // Default ke Komersil jika kosong
                        'address' => $alamat,
                        'district_code' => $district->code,
                        'village_code' => $district->villages()->first()->code, // KETERBATASAN: Mengambil desa pertama dari kecamatan
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'description' => 'Data diimpor secara otomatis.'
                    ]
                );

                // Tambahkan Tipe Rumah jika ada informasinya
                if (!empty($tipeRumah)) {
                    HouseType::firstOrCreate(
                        [
                            'housing_project_id' => $project->id,
                            'name' => $tipeRumah
                        ],
                        [
                            'status' => 'Ready Stock',
                            'price' => 0, // KETERBATASAN: Harga tidak ada di CSV
                            'total_units' => 1, // KETERBATASAN: Jumlah unit tidak ada di CSV
                        ]
                    );
                }
                
                DB::commit();
                $this->info("Berhasil mengimpor: {$namaPerumahan}");
                $count++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal mengimpor data untuk '{$row[1]}'. Error: " . $e->getMessage());
                Log::error("Import Error for row: " . implode(',', $row) . " | " . $e->getMessage());
            }
        }

        fclose($file);
        $this->info("---------------------------------");
        $this->info("Impor data selesai. {$count} data berhasil dimasukkan.");
        return 0;
    }
}