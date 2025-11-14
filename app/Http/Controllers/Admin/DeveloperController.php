<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Models\User; // <-- PENTING: Pastikan ini ada

class DeveloperController extends Controller
{

    // GANTI DENGAN KODE INI
    public function __construct()
    {
        // 1. Pastikan user sudah login
        $this->middleware('auth');

        // 2. Pastikan HANYA role 'admin' yang bisa mengakses controller ini
        $this->middleware(['role:admin']);
    }
    public function index()
    {
        // Ambil relasi 'user' agar bisa ditampilkan di tabel
        $developers = Developer::with('user')->paginate(10);
        return view('admin.developers.index', compact('developers'));
    }

    public function create()
    {
        // Ambil semua user yang punya role 'developer' DAN BELUM punya profil
        // Gunakan sintaks function() lama
        $users = User::whereHas('roles', function ($q) {
                        $q->where('name', 'developer');
                    })
                    ->doesntHave('developer')
                    ->get();

        return view('admin/developers/create', compact('users')); // Kirim $users
    }

    public function store(Request $request)
    {
        // VALIDASI 'user_id' SUDAH DITAMBAHKAN DI SINI
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:developers,user_id', // WAJIB
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Developer::create($validated); // $validated sekarang berisi 'user_id'

        return redirect()->route('admin.developers.index')->with('success', 'Data developer berhasil ditambahkan.');
    }

    public function edit(Developer $developer)
    {
        // Ambil user SAAT INI + user lain yang belum punya profil
        // Gunakan sintaks function() lama
        $users = User::whereHas('roles', function ($q) {
                        $q->where('name', 'developer');
                    })
                    ->where(function ($query) use ($developer) {
                        $query->doesntHave('developer') // Yg belum punya profil
                                ->orWhere('id', $developer->user_id); // ATAU user yg saat ini
                    })
                    ->get();

        return view('admin/developers/edit', compact('developer', 'users')); // Kirim $users
    }

    public function update(Request $request, Developer $developer)
    {
        // VALIDASI 'user_id' SUDAH DITAMBAHKAN DI SINI
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:developers,user_id,' . $developer->id, // WAJIB
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $developer->update($validated);

        return redirect()->route('admin.developers.index')->with('success', 'Data developer berhasil diperbarui.');
    }

    public function destroy(Developer $developer)
    {
        $developer->delete();
        return redirect()->route('admin.developers.index')->with('success', 'Data developer berhasil dihapus.');
    }
}
