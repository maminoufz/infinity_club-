<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    use HasFactory;

    // Specify the table name if it differs from the plural form of the model name
    protected $table = 'specialites'; // Optional if the table is named `specialites`

    // Specify the fillable fields for mass assignment
    protected $fillable = ['nom_sp', 'id_dep'];

    // A specialite belongs to a department
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_dep');
    }

    // A specialite has many users
    public function users()
    {
        return $this->hasMany(User::class, 'id_sp');
    }
    public function images()
    {
        return $this->hasMany(Image::class, 'id_sp');
    }
}
