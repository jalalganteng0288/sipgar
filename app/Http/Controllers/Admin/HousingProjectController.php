<?php

namespace App\Http\Controllers\Admin;
use App\Models\HouseType;
use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// --- PERBAIKAN: Gunakan model dari Laravolt ---
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
// ------------------------------------------
use Illuminate\Support\Facades\Auth;
use App\Models\Developer;
use App\Models\User;


class HousingProjectController extends Controller
{

    /**
     * Menerapkan Policy secara otomatis setelah user terotentikasi.
     * Ini MENGHINDARI error 'Call to a member function hasRole() on null'.
     */
    public function __construct()
    {
        // Pastikan user sudah terautentikasi
        $this->middleware('auth');

        // Jalankan authorizeResource setelah user sudah tersedia
        // Jadi policy akan diterapkan dengan benar (setelah Auth::user() tersedia)
        $this->middleware(function ($request, $next) {
            // authorizeResource membutuhkan model class dan nama parameter route ('project')
            $this->authorizeResource(HousingProject::class, 'project');
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * Memfilter daftar proyek berdasarkan role pengguna.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        // 1. Inisialisasi query dasar dengan relasi utama
        $query = HousingProject::with(['developer', 'district', 'houseTypes']);

        // 2. === FILTER ROLE (Sesuai kode asli kamu) ===
        if ($user->hasRole('developer')) {
            if ($user->developer) {
                $query->where('developer_id', $user->developer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // 3. === FILTER PENCARIAN ===
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('developer_name', 'like', "%{$search}%");
            });
        }

        // 4. Ambil data dengan paginasi
        $projects = $query->latest()->paginate(9);

        // 5. MANIPULASI DATA (Menghitung manual agar PASTI tidak error query)
        $projects->getCollection()->transform(function ($project) {
            // Hitung Total Unit dari relasi houseTypes
            $project->total_units = $project->houseTypes->sum('total_units');

            // Hitung Unit Tersedia dari kolom ready_stock
            $project->available_units = $project->houseTypes->sum('ready_stock');

            // Hitung Jumlah Tipe Rumah
            $project->house_types_count = $project->houseTypes->count();

            return $project;
        });

        return view('admin.projects.index', compact('projects', 'search'));
    }


    public function create()
    {
        // Ambil semua developer (untuk dropdown Developer)
        $developers = Developer::all(); // <-- 2. AMBIL SEMUA DEVELOPER
        // --- PERBAIKAN: Gunakan model Laravolt dan filter Garut ---
        $districts = \Laravolt\Indonesia\Models\District::where('city_code', env('APP_CITY_CODE', '3205'))
            ->pluck('name', 'code');
        // -----------------------------------------------------

        // Kirim villages kosong karena belum memilih kecamatan
        $villages = collect();

        return view('admin.projects.create', compact('developers', 'districts', 'villages'));
    }



    public function store(Request $request, HousingProject $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'total_units' => 'required|integer|min:0',
            'ready_stock' => 'required|integer|min:0', // TAMBAHKAN INI
            'description' => 'nullable|string',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'floor_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('house-type-images', 'public');
        }
        if ($request->hasFile('floor_plan')) {
            $validated['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        }

        $project->houseTypes()->create($validated);

        return redirect()->route('admin.projects.show', $project->id)->with('success', 'Tipe rumah berhasil ditambahkan.');
    }

    public function show(HousingProject $project)
    {
        // Otorisasi sudah di handle oleh __construct()
        $project->load('houseTypes');
        return view('admin.projects.show', ['project' => $project]);
    }

    public function edit(HousingProject $project)
    {
        // Otorisasi sudah di handle oleh __construct()

        // --- PERBAIKAN: Gunakan model Laravolt ---
        $districts = \Laravolt\Indonesia\Models\District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
        $villages = \Laravolt\Indonesia\Models\Village::where('district_code', $project->district_code)->pluck('name', 'code');
        // ----------------------------------------

        $developers = Developer::all();

        return view('admin.projects.edit', [
            'project' => $project,
            'districts' => $districts,
            'villages' => $villages,
            'developers' => $developers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HouseType $houseType)
    {
        $this->authorize('update', $houseType->housingProject);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'total_units' => 'required|integer|min:0',
            'ready_stock' => 'required|integer|min:0', // PASTIKAN INI ADA
            'land_area' => 'required|numeric|min:0',   // TAMBAHKAN KEMBALI INI
            'building_area' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'floor_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Logika upload foto tipe rumah
        if ($request->hasFile('image')) {
            if ($houseType->image) {
                Storage::disk('public')->delete($houseType->image);
            }
            $validated['image'] = $request->file('image')->store('house-type-images', 'public');
        }

        // Logika upload denah
        if ($request->hasFile('floor_plan')) {
            if ($houseType->floor_plan) {
                Storage::disk('public')->delete($houseType->floor_plan);
            }
            $validated['floor_plan'] = $request->file('floor_plan')->store('floor-plans', 'public');
        }

        $houseType->update($validated);

        return redirect()->route('admin.projects.show', $houseType->housing_project_id)
            ->with('success', 'Tipe rumah berhasil diperbarui.');
    }

    public function destroy(HousingProject $project)
    {
        // Otorisasi sudah di handle oleh __construct__
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->site_plan) {
            Storage::disk('public')->delete($project->site_plan);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil dihapus.');
    }


    public function getDevelopers()
    {

        $developers = User::whereHas('roles', function ($q) {
            $q->where('name', 'developer');
        })
            ->with('developer') // 'developer' adalah nama relasi di User.php
            ->get();

        return response()->json($developers);
    }
    public function getVillages($districtCode)
    {
        // Gunakan model Laravolt\Indonesia\Models\Village yang sudah di-import di atas
        // Kita pluck name keyed by code supaya frontend bisa langsung iterasi { code: name }
        $villages = \Laravolt\Indonesia\Models\Village::where('district_code', $districtCode)
            ->pluck('name', 'code');

        return response()->json($villages);
    }
    public function approve(HousingProject $project)
    {
        $project->update(['status' => 'approved']);

        return back()->with('success', 'Proyek perumahan berhasil disetujui.');
    }
}
