<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['image_path', 'id_event'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}
