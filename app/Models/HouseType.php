<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'housing_project_id',
        'name',
        'status',
        'image', // Tambahkan ini
        'floor_plan', // Tambahkan ini
        'price',
        'land_area',
        'building_area',
        'total_units',
        'specifications',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke HousingProject.
     */
    public function housingProject()
    {
        return $this->belongsTo(HousingProject::class);
    }
}
