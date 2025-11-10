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
        'user_id',
        'company_name',
        'nib',
        'address',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi "hasMany" ke HousingProject.
     */
    public function housingProjects()
    {
        return $this->hasMany(HousingProject::class);
    }
}