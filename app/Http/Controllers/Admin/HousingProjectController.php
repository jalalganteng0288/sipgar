<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
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
        // Panggil authorizeResource hanya setelah user terotentikasi.
        // Ini memastikan Auth::user() sudah tersedia sebelum Policy diinisialisasi.
        $this->middleware(function ($request, $next) {
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
        $query = HousingProject::query();

        // LOGIKA FILTER BERDASARKAN ROLE
        if (Auth::user()->hasRole('developer')) {
            // Ambil relasi developer
            $developer = Auth::user()->developer; 
            
            // PERBAIKAN: Cek apakah relasi developer ada (mengatasi error null property access)
            if ($developer) {
                // Developer hanya melihat proyek yang developer_id-nya miliknya
                $query->where('developer_id', $developer->id);
            } else {
                // Jika data Developer belum ada, tampilkan hasil kosong.
                $query->where('id', 0); 
            }
        }
        
        // Logika pencarian lama dipertahankan
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('developer_name', 'like', '%' . $search . '%');
            });
        }

        $projects = $query->latest()->paginate(10);
        $projects->appends(['search' => $search]);

        return view('admin.projects.index', [
            'projects' => $projects,
            'search' => $search,
        ]);
    }
    
    public function create()
    {
        // Otorisasi sudah di handle oleh __construct()
        $districts = District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
        return view('admin.projects.create', compact('districts'));
    }

    public function store(Request $request)
    {
        // Otorisasi sudah di handle oleh __construct()
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'type' => 'required|string|in:Komersil,Subsidi',
            'address' => 'required|string',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'site_plan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $validated;

        // LOGIKA PENGISIAN developer_id (Dipertahankan dan diperbaiki)
        if (Auth::user()->hasRole('developer')) {
            $developer = Auth::user()->developer;
            
            // PERBAIKAN: Cek apakah relasi developer ada
            if ($developer) {
                $data['developer_id'] = $developer->id;
                $data['developer_name'] = $developer->company_name; 
            } else {
                // Jika developer tidak punya data perusahaan, tolak aksi
                return redirect()->back()->withErrors(['developer_data' => 'Data perusahaan Pengembang Anda belum lengkap. Harap lengkapi profil perusahaan sebelum membuat proyek.'])->withInput();
            }
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('project-images', 'public');
        }
        if ($request->hasFile('site_plan')) {
            $data['site_plan'] = $request->file('site_plan')->store('site-plans', 'public');
        }

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
        $districts = District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
        $villages = Village::where('district_code', $project->district_code)->pluck('name', 'code');

        return view('admin.projects.edit', [
            'project' => $project,
            'districts' => $districts,
            'villages' => $villages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HousingProject $project)
    {
        // Otorisasi sudah di handle oleh __construct()
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'type' => 'required|string|in:Komersil,Subsidi',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'site_plan' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'district_code' => 'required|exists:districts,code',
            'village_code' => 'required|exists:villages,code',
        ]);
        
        // Jika developer yang mengupdate, timpa developer_name dengan nama perusahaan yang benar
        if (Auth::user()->hasRole('developer')) {
            $developer = Auth::user()->developer;
            if ($developer) {
                $validatedData['developer_name'] = $developer->company_name;
            }
        }

        // Proses upload file (Logika lama dipertahankan)
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

        $project->update($validatedData);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }


    public function destroy(HousingProject $project)
    {
        // Otorisasi sudah di handle oleh __construct()
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->site_plan) {
            Storage::disk('public')->delete($project->site_plan);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil dihapus.');
    }
}