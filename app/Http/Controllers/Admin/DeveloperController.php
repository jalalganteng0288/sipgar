<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Models\User; // <-- PENTING: Pastikan ini ada

class DeveloperController extends Controller
{
    public function index()
    {
        // Ambil relasi 'user' agar bisa ditampilkan di tabel
        $developers = Developer::with('user')->paginate(10);
        return view('admin.developers.index', compact('developers'));
    }

    public function create()
    {
        // Ambil semua user yang punya role 'developer' DAN BELUM punya profil
        // Kita gunakan 'whereHas' untuk menggantikan scope 'role()'
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'developer'))
            ->doesntHave('developer')
            ->get();

        return view('admin.developers.create', compact('users')); // Kirim $users
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
        // Kita gunakan 'whereHas' untuk menggantikan scope 'role()'
        $users = User::whereHas('roles', fn($q) => $q->where('name', 'developer'))
                    ->where(function ($query) use ($developer) {
                        $query->doesntHave('developer') // Yg belum punya profil
                                ->orWhere('id', $developer->user_id); // ATAU user yg saat ini
                    })
                    ->get();

        return view('admin.developers.edit', compact('developer', 'users')); // Kirim $users
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
