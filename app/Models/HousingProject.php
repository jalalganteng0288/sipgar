<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\District;

class HousingProject extends Model
{
    // TAMBAHKAN KODE INI
    protected $fillable = [
        'name',
        'address',
        'description',
        'developer_name',
        'image',
        'latitude',       // <-- Tambahkan ini
        'longitude',
        'district_code', // Pastikan ini ada
        'village_code',
    ];

    public function houseTypes()
    {
        return $this->hasMany(HouseType::class);
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke District.
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }
}
