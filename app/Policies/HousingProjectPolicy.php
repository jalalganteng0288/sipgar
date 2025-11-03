<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HousingProject;
use Illuminate\Auth\Access\Response;

class HousingProjectPolicy
{
    /**
     * Lakukan pemeriksaan sebelum semua cek otorisasi lainnya.
     * Admin Disperkim selalu bisa melewati semua cek.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('disperkim_admin')) {
            return true; // Admin Disperkim selalu diizinkan
        }
        return null;
    }


    /**
     * Tentukan apakah pengguna dapat melihat daftar model.
     */
    public function viewAny(User $user): bool
    {
        // Semua pengguna yang memiliki role admin/developer sudah diizinkan oleh middleware di routes/web.php
        // Jadi, cukup mengembalikan true di sini (kecuali kita mau membatasi Developer di viewAny, tapi ini tidak disarankan untuk efisiensi query)
        return true;
    }

    /**
     * Tentukan apakah pengguna dapat melihat model tertentu.
     */
    public function view(User $user, HousingProject $housingProject): bool
    {
        // Hanya izinkan jika developer_id proyek cocok dengan id developer user
        if ($user->hasRole('developer')) {
            return $user->developer->id === $housingProject->developer_id;
        }

        return false; // Seharusnya sudah tertangkap oleh before(), tapi untuk keamanan
    }

    /**
     * Tentukan apakah pengguna dapat membuat model.
     * Pengembang diperbolehkan membuat proyek baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('developer');
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui model.
     */
    public function update(User $user, HousingProject $housingProject): bool
    {
        // Pengembang hanya boleh update proyek yang dia miliki
        if ($user->hasRole('developer')) {
            return $user->developer->id === $housingProject->developer_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus model.
     */
    public function delete(User $user, HousingProject $housingProject): bool
    {
        // Pengembang hanya boleh delete proyek yang dia miliki
        if ($user->hasRole('developer')) {
            return $user->developer->id === $housingProject->developer_id;
        }

        return false;
    }
}