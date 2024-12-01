<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Tymon\JWTAuth\Contracts\JWTSubject; // Import JWTSubject

class User extends Authenticatable implements JWTSubject // Implement JWTSubject
{
    use HasFactory, HasProfilePhoto, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'bio',
        'id_sp',
        'facebook_link',
        'twitter_link',
        'linkedin_link',
        'instagram_link',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: Specialite.
     */
    public function specialite()
    {
        return $this->belongsTo(Specialite::class, 'id_sp');
    }
    /**
 * Relationship: User belongs to a link.
 */
public function link()
{
    return $this->belongsTo(Link::class, 'id_link');
}



    /**
     * Get the identifier that will be stored in the JWT token.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Default to the user's primary key (id)
    }

    /**
     * Get the custom claims to be added to the JWT payload.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role, // You can include custom claims, like the user's role
        ];
    }
    public function images()
    {
        return $this->hasMany(Image::class, 'id_user');
    }
}
