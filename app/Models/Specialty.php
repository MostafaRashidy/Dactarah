<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon' 
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
