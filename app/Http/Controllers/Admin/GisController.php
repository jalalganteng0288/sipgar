<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;

class GisController extends Controller
{
    public function index()
    {
        // 1. Ambil data proyek yang punya koordinat.
        $projects = HousingProject::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // 2. Ubah data menjadi format GeoJSON
        $geoJsonData = $projects->map(function ($project) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$project->longitude, (float)$project->latitude]
                ],
                'properties' => [
                    'name' => $project->name,
                    'type' => $project->type, // Ambil tipe (Subsidi/Komersil)
                    'status' => $project->status, // Ambil status (Approved/Pending/etc)
                    'url' => route('projects.show', $project->id)
                ]
            ];
        });

        return view('admin.gis.index', ['geoJsonData' => $geoJsonData]);
    }
}