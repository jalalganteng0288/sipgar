<?php

namespace App\Http\Controllers\Admin;

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

        $query = HousingProject::with(['developer', 'houseTypes', 'district'])
            ->withCount('houseTypes'); // Hitung jumlah tipe rumah

        // === FILTER ROLE ===
        if ($user->hasRole('developer')) {
            if ($user->developer) {
                $query->where('developer_id', $user->developer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // === FILTER PENCARIAN ===
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%') // <-- disesuaikan dengan kolom di DB
                    ->orWhereHas('developer', function ($q2) use ($search) {
                        $q2->where('company_name', 'like', '%' . $search . '%');
                    });
            });
        }

        // === PAGINASI ===
        $projects = $query->latest()->paginate(10);

        return view('admin.projects.index', compact('projects', 'search'));
    }



    public function create()
    {
        // Ambil semua developer (untuk dropdown Developer)
        $developers = \App\Models\Developer::all();

        // --- PERBAIKAN: Gunakan model Laravolt dan filter Garut ---
        $districts = \Laravolt\Indonesia\Models\District::where('city_code', env('APP_CITY_CODE', '3205'))
            ->pluck('name', 'code');
        // -----------------------------------------------------

        // Kirim villages kosong karena belum memilih kecamatan
        $villages = collect();

        return view('admin.projects.create', compact('developers', 'districts', 'villages'));
    }



    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Aturan validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Komersil,Subsidi',
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'site_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // 2. Sesuaikan aturan validasi berdasarkan role
        if ($user->hasRole('admin')) {
            // Admin WAJIB memilih developer_id dari dropdown
            $rules['developer_id'] = 'required|exists:developers,id';
        }

        // 3. Validasi request
        $validated = $request->validate($rules);
        $data = $validated; // Data awal adalah data yang tervalidasi

        // 4. Logika khusus untuk role
        if ($user->hasRole('admin')) {
            // Jika Admin, ambil developer_name dari developer_id yang dipilih
            $developer = Developer::find($validated['developer_id']);
            $data['developer_name'] = $developer->company_name;
            // $data['developer_id'] sudah ada dari $validated
        } elseif ($user->hasRole('developer')) {
            $developer = $user->developer; // Ambil relasi developer dari user

            if (!$developer) {
                return redirect()->back()
                    ->withErrors(['developer_data' => 'Data perusahaan Anda belum lengkap. Harap lengkapi profil perusahaan sebelum membuat proyek.'])
                    ->withInput();
            }

            // Timpa/isi data developer_id dan developer_name
            $data['developer_id'] = $developer->id;
            $data['developer_name'] = $developer->company_name; // Ambil nama resmi
        }

        // 5. Proses upload file
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('project-images', 'public');
        }
        if ($request->hasFile('site_plan')) {
            $data['site_plan'] = $request->file('site_plan')->store('site-plans', 'public');
        }

        // 6. Buat proyek
        HousingProject::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil ditambahkan.');
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
    public function update(Request $request, HousingProject $project)
    {
        $user = Auth::user();

        // 1. Aturan validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Komersil,Subsidi',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'site_plan' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
        ];

        // 2. Sesuaikan aturan validasi
        if ($user->hasRole('admin')) {
            $rules['developer_id'] = 'required|exists:developers,id';
        }

        // 3. Validasi
        $validatedData = $request->validate($rules);

        // 4. Logika khusus untuk role
        if ($user->hasRole('admin')) {
            // Jika Admin, ambil developer_name dari developer_id yang dipilih
            $developer = Developer::find($validatedData['developer_id']);
            $validatedData['developer_name'] = $developer->company_name;
        } elseif ($user->hasRole('developer')) {
            $developer = $user->developer;
            if ($developer) {
                // Paksa ID dan Nama agar sesuai dengan profil login
                $validatedData['developer_id'] = $developer->id;
                $validatedData['developer_name'] = $developer->company_name;
            }
        }

        // 5. Proses upload file (Hapus file lama jika ada)
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validatedData['image'] = $request->file('image')->store('project-images', 'public');
        }

        if ($request->hasFile('site_plan')) {
            if ($project->site_plan) {
                Storage::disk('public')->delete($project->site_plan);
            }
            $validatedData['site_plan'] = $request->file('site_plan')->store('site-plans', 'public');
        }

        // 6. Update proyek
        $project->update($validatedData);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
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

    public function getVillages($districtCode)
    {
        $villages = \Laravolt\Indonesia\Models\Village::where('district_code', $districtCode)
            ->pluck('name', 'code');

        // kembalikan JSON { code => name }
        return response()->json($villages);
    }
}
