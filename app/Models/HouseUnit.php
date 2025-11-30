<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika Anda menggunakan factory
use Illuminate\Database\Eloquent\Model;

class HouseUnit extends Model
{
    use HasFactory;
    
    // Pastikan nama tabel benar, jika Anda tidak menggunakan konvensi default Laravel (house_units)
    // protected $table = 'house_units';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     */
    protected $fillable = [
        'house_type_id',
        'unit_number', 
        'status',      // Kritis: Misal: 'Tersedia', 'Terjual', 'Cadangan'
        'purchase_date',
    ];
    
    // Relasi ke HouseType
    public function houseType()
    {
        return $this->belongsTo(HouseType::class);
    }
}