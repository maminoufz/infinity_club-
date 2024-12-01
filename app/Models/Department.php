<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['nom_dep']; // The department name

    // A department has many specialites
    public function specialites()
    {
        return $this->hasMany(Specialite::class, 'id_dep'); // foreign key: id_dep
    }

    // A department has many events
    public function events()
    {
        return $this->hasMany(Event::class, 'id_dep'); // foreign key: id_dep
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'id_dep');
    }
}
