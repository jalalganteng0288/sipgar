<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'district',
    ];
}
