<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use App\Models\User;

class DeveloperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        $developers = Developer::with('user')->paginate(10);
        return view('admin.developers.index', compact('developers'));
    }

    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
                        $q->where('name', 'developer');
                    })
                    ->doesntHave('developer')
                    ->get();

        return view('admin.developers.create', compact('users'));
    }

    public function store(Request $request)
    {
        // VALIDASI TANPA CONTACT_PERSON
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:developers,user_id',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Developer::create($validated);

        return redirect()->route('admin.developers.index')
                         ->with('success', 'Data developer berhasil ditambahkan.');
    }

    public function edit(Developer $developer)
    {
        $users = User::whereHas('roles', function ($q) {
                        $q->where('name', 'developer');
                    })
                    ->where(function ($query) use ($developer) {
                        $query->doesntHave('developer')
                              ->orWhere('id', $developer->user_id);
                    })
                    ->get();

        return view('admin.developers.edit', compact('developer', 'users'));
    }

    public function update(Request $request, Developer $developer)
    {
        // VALIDASI TANPA CONTACT_PERSON
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:developers,user_id,' . $developer->id,
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $developer->update($validated);

        return redirect()->route('admin.developers.index')
                         ->with('success', 'Data developer berhasil diperbarui.');
    }

    public function destroy(Developer $developer)
    {
        $developer->delete();

        return redirect()->route('admin.developers.index')
                         ->with('success', 'Data developer berhasil dihapus.');
    }
}
