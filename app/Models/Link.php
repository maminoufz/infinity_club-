<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name_link', 'url'];

    /**
     * A link belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
