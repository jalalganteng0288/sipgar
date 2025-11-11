<?php

namespace App\Policies;

// File: app/Policies/HousingProjectPolicy.php

use App\Models\User;
use App\Models\HousingProject;

class HousingProjectPolicy
{
    // Admin (true) dapat melewati semua cek
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function view(User $user, HousingProject $project): bool
    {
        // Developer hanya dapat melihat detail proyek miliknya
        return $user->developer && $user->developer->id === $project->developer_id;
    }

    public function update(User $user, HousingProject $project): bool
    {
        // Developer hanya dapat mengedit proyek miliknya
        return $user->developer && $user->developer->id === $project->developer_id;
    }

    public function delete(User $user, HousingProject $project): bool
    {
        // Developer hanya dapat menghapus proyek miliknya
        return $user->developer && $user->developer->id === $project->developer_id;
    }
}
