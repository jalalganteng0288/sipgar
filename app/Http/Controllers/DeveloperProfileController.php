<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class DeveloperProfileController extends Controller
{
    /**
     * Update the developer's profile information.
     * * INI ADALAH FUNGSI 'update' YANG HILANG
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Validasi data developer
        $developerData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // 2. 'updateOrCreate' akan mengupdate data jika ada,
        //    atau membuat data baru jika belum ada.
        $request->user()->developer()->updateOrCreate(
            ['user_id' => $request->user()->id], // Kondisi pencarian
            $developerData                      // Data untuk diupdate/dibuat
        );

        // 3. Kembali ke halaman profil
        return Redirect::route('profile.edit')->with('status', 'developer-profile-updated');
    }
}