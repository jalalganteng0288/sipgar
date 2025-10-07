<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class HousingProject extends Model
{
    // TAMBAHKAN KODE INI
    protected $fillable = [
        'name',
        'address',
        'description',
        'developer_name',
        'type',
        'image',
        'site_plan',
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

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'code');
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }
}
