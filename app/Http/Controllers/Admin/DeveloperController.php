<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeveloperController extends Controller
{
    public function __construct()
    {
        // Memastikan hanya user yang terotentikasi dan memiliki role 'admin' yang bisa mengakses
        $this->middleware('auth');
        $this->middleware(['role:admin']);
    }

    // Menampilkan daftar semua developer
    public function index()
    {
        $developers = Developer::with('user')->paginate(10);
        return view('admin.developers.index', compact('developers'));
    }

    // Menampilkan form penambahan developer baru
    public function create()
    {
        // Karena menggunakan metode Satu Langkah, tidak perlu query user yang belum tertaut
        return view('admin.developers.create');
    }

    // Menyimpan user dan entitas developer (Satu Langkah)
    public function store(Request $request)
    {
        // 1. Validasi gabungan data User dan Perusahaan
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255', 
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // 2. Buat Akun User Baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 3. Berikan Role 'developer'
            $developerRole = Role::where('name', 'developer')->first();
            if ($developerRole) {
                $user->assignRole($developerRole);
            }

            // 4. Buat Entitas Developer dan Tautkan
            Developer::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                // Menggunakan 'name' user jika 'contact_person' kosong
                'contact_person' => $validated['contact_person'] ?? $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            DB::commit();

            return redirect()->route('admin.developers.index')
                            ->with('success', 'Developer baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Anda dapat logging error $e->getMessage() di sini
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gagal menambahkan Developer. Terjadi kesalahan sistem.');
        }
    }

    // Menampilkan form edit developer (Mengedit User dan Developer)
    public function edit(Developer $developer)
    {
        // Cek apakah user terkait ada sebelum menampilkan form
        if (!$developer->user) {
            return redirect()->route('admin.developers.index')
                             ->with('error', 'Tidak dapat mengedit: User tertaut tidak ditemukan.');
        }
        
        return view('admin.developers.edit', compact('developer'));
    }

    // Memperbarui user dan entitas developer (Satu Langkah)
    public function update(Request $request, Developer $developer)
    {
        // 1. Validasi gabungan data User dan Perusahaan
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $developer->user_id, // Exclude current user's email
            'password' => 'nullable|string|min:8', // Password boleh kosong
            
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $user = $developer->user;
            
            // 2. Update Akun User
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            // 3. Update Entitas Developer
            $developer->update([
                'company_name' => $validated['company_name'],
                'contact_person' => $validated['contact_person'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            DB::commit();

            return redirect()->route('admin.developers.index')
                             ->with('success', 'Data Developer dan Akun User berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Developer.');
        }
    }
    
    // Metode baru untuk mengaktifkan/menonaktifkan (Suspend) akses developer
    public function toggleRole(Developer $developer)
    {
        $user = $developer->user;

        if ($user) {
            $roleName = 'developer';

            if ($user->hasRole($roleName)) {
                // Cabut role developer (Suspend/Nonaktifkan)
                $user->removeRole($roleName);
                $message = 'Akses developer untuk ' . $user->name . ' telah dicabut (Nonaktif).';
            } else {
                // Berikan role developer (Aktifkan kembali)
                $user->assignRole($roleName);
                $message = 'Akses developer untuk ' . $user->name . ' telah diaktifkan kembali.';
            }

            return redirect()->route('admin.developers.index')->with('success', $message);
        }

        return redirect()->route('admin.developers.index')->with('error', 'User terkait tidak ditemukan.');
    }

    // Menghapus developer dan user terkait secara permanen
    public function destroy(Developer $developer)
    {
        try {
            DB::beginTransaction();
            
            // 1. Hapus User terkait
            $user = $developer->user;
            if ($user) {
                $user->removeRole('developer'); 
                $user->delete();
            }
            
            // 2. Hapus entitas Developer
            $developer->delete();

            DB::commit();
            
            return redirect()->route('admin.developers.index')
                             ->with('success', 'Developer dan akun user terkait berhasil dihapus total.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Developer.');
        }
    }
}