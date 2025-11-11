<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Penting untuk validasi update

class DeveloperController extends Controller
{
    /**
     * Menampilkan daftar semua developer.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Developer::with('user'); // Load relasi user

        if ($search) {
            $query->where('company_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        $developers = $query->latest()->paginate(10);

        return view('admin.developers.index', compact('developers', 'search'));
    }

    /**
     * Menampilkan form untuk membuat developer baru.
     */
    public function create()
    {
        // Ambil user dengan role 'developer' YANG BELUM PUNYA data developer
        $users = User::role('developer')->doesntHave('developer')->get();
        
        return view('admin.developers.create', compact('users'));
    }

    /**
     * Menyimpan developer baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20', // Anda bisa tambahkan NIB di sini jika perlu
            
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('developers', 'user_id') 
            ],
        ]);

        Developer::create($validated);

        return redirect()->route('admin.developers.index')->with('success', 'Data developer berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail developer (jika diperlukan).
     */
    public function show(Developer $developer)
    {
         // Load relasi proyek jika perlu
        $developer->load('user', 'housingProjects');
        return view('admin.developers.show', compact('developer'));
    }

    /**
     * Menampilkan form untuk mengedit developer.
     */
    public function edit(Developer $developer)
    {
        $currentUserId = $developer->user_id;

        $users = User::role('developer')
            ->where(function($query) use ($currentUserId) {
                $query->doesntHave('developer') // yang belum punya developer
                      ->orWhere('id', $currentUserId); // atau user saat ini
            })
            ->get();

        return view('admin.developers.edit', compact('developer', 'users'));
    }

    /**
     * Memperbarui data developer di database.
     */
    public function update(Request $request, Developer $developer)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('developers', 'user_id')->ignore($developer->id)
            ],
        ]);

        $developer->update($validated);

        return redirect()->route('admin.developers.index')->with('success', 'Data developer berhasil diperbarui.');
    }

    /**
     * Menghapus data developer dari database.
     */
    public function destroy(Developer $developer)
    {
        // Proteksi jika developer masih punya proyek
        if ($developer->housingProjects()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Developer tidak bisa dihapus karena masih memiliki data proyek.']);
        }
        
        $developer->delete();

        return redirect()->route('admin.developers.index')->with('success', 'Data developer berhasil dihapus.');
    }
}