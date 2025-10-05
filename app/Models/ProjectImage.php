<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'housing_project_id',
        'path',
    ];

    // Definisikan relasi sebaliknya ke HousingProject (opsional tapi baik untuk dilakukan)
    public function housingProject()
    {
        return $this->belongsTo(HousingProject::class);
    }
}
