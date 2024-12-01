<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Define the attributes that are mass assignable
    protected $fillable = ['type', 'date', 'id_dep','description'];

    // Define the relationship: An event belongs to a department
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_dep');
    }
    public function image(){

        return $this->belongsTo(Department::class, 'id_event');

    }
}
