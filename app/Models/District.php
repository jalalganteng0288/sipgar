<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts'; // pastikan tabel sesuai di database
    protected $primaryKey = 'code'; // kalau kolom primary key kamu 'code'
    public $incrementing = false; // karena 'code' bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'city_code',
    ];
}
