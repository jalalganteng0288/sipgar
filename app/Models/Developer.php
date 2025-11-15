<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // <-- TAMBAHKAN BARIS INI
        'company_name',
        'contact_person',
        'phone',
        'address',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke User.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }


    /**
     * Mendefinisikan relasi "hasMany" ke HousingProject.
     */
    public function housingProjects()
    {
        return $this->hasMany(HousingProject::class);
    }
}
