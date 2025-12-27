<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingProject;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class HousingProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Gunakan authorizeResource hanya untuk metode bawaan (index, create, store, dll)
        // Kecualikan 'approve' agar kita bisa atur manual di fungsinya
        $this->middleware(function ($request, $next) {
            $this->authorizeResource(HousingProject::class, 'project', [
                'except' => ['approve']
            ]);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        $query = HousingProject::with(['developer', 'district', 'houseTypes']);

        if ($user->hasRole('developer')) {
            if ($user->developer) {
                $query->where('developer_id', $user->developer->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('developer_name', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(9);

        // Menghitung statistik unit untuk ditampilkan di kartu (Dashboard Admin)
        $projects->getCollection()->transform(function ($project) {
            $project->total_units = $project->houseTypes->sum('total_units');
            $project->available_units = $project->houseTypes->sum('ready_stock');
            $project->house_types_count = $project->houseTypes->count();
            return $project;
        });

        return view('admin.projects.index', compact('projects', 'search'));
    }

    public function create()
    {
        $developers = Developer::all();
        $districts = District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
        $villages = collect();

        return view('admin.projects.create', compact('developers', 'districts', 'villages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string',
            'address'       => 'required|string',
            'district_code' => 'required|string',
            'village_code'  => 'required|string',
            'description'   => 'nullable|string',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'site_plan'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'developer_id'  => Auth::user()->hasRole('admin') ? 'required|exists:developers,id' : 'nullable',
        ]);

        if (Auth::user()->hasRole('developer')) {
            $validated['developer_id'] = Auth::user()->developer->id;
            $validated['developer_name'] = Auth::user()->developer->company_name;
        } else {
            $dev = Developer::find($request->developer_id);
            $validated['developer_name'] = $dev->company_name ?? 'Unknown';
        }

        $validated['status'] = 'pending';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects/images', 'public');
        }
        if ($request->hasFile('site_plan')) {
            $validated['site_plan'] = $request->file('site_plan')->store('projects/site_plans', 'public');
        }

        HousingProject::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek perumahan berhasil ditambahkan.');
    }

    public function show(HousingProject $project)
    {
        $project->load('houseTypes');
        return view('admin.projects.show', ['project' => $project]);
    }

    public function edit(HousingProject $project)
    {
        $districts = District::where('city_code', env('APP_CITY_CODE', '3205'))->pluck('name', 'code');
        $villages = Village::where('district_code', $project->district_code)->pluck('name', 'code');
        $developers = Developer::all();

        return view('admin.projects.edit', compact('project', 'districts', 'villages', 'developers'));
    }

    public function update(Request $request, HousingProject $project)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string',
            'address'       => 'required|string',
            'district_code' => 'required|string',
            'village_code'  => 'required|string',
            'description'   => 'nullable|string',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'site_plan'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'developer_id'  => Auth::user()->hasRole('admin') ? 'required|exists:developers,id' : 'nullable',
        ]);

        if (Auth::user()->hasRole('admin') && $request->developer_id) {
            $dev = Developer::find($request->developer_id);
            $validated['developer_name'] = $dev->company_name;
        }

        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $validated['image'] = $request->file('image')->store('projects/images', 'public');
        }

        if ($request->hasFile('site_plan')) {
            if ($project->site_plan) Storage::disk('public')->delete($project->site_plan);
            $validated['site_plan'] = $request->file('site_plan')->store('projects/site_plans', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil diperbarui.');
    }

    public function destroy(HousingProject $project)
    {
        if ($project->image) Storage::disk('public')->delete($project->image);
        if ($project->site_plan) Storage::disk('public')->delete($project->site_plan);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Data perumahan berhasil dihapus.');
    }

    public function getVillages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)->pluck('name', 'code');
        return response()->json($villages);
    }

    public function approve(HousingProject $project)
    {
        // Cek: Kalau bukan admin, tendang keluar!
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui lokasi ini.');
        }

        $project->update(['status' => 'approved']);

        return back()->with('success', 'Proyek perumahan berhasil disetujui oleh Admin.');
    }
}
