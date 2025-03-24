<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $fillable = ['name', 'name_ar', 'name_en'];

    // Relationship with doctors
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
