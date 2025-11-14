<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Developer;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // === PERUBAHAN DI SINI ===

        // 1. Ambil user yang sedang login
        $user = $request->user();

        // 2. Ambil data developer yang terhubung dengan user
        //    'firstOrNew' akan membuat objek Developer kosong jika
        //    datanya belum ada, ini mencegah error pada form.
        $developer = $user->developer()->firstOrNew([]); //

        // 3. Kirim data user DAN data developer ke view
        return view('profile.edit', [
            'user' => $user,
            'developer' => $developer, // <-- TAMBAHAN KUNCI
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // HAPUS SEMUA LOGIKA IF 'developer' DARI SINI

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ... (Tidak perlu diubah, biarkan seperti aslinya) ...
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
