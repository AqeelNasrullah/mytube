<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'avatar', 'name', 'phone_number', 'address', 'city', 'state', 'postal_code', 'country_id', 'user_id'
    ];

    /**
     * Database Relations
     */

    // Channel belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Channel belongs to country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Channel has many videos
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // Channel belongs to many users
    public function manyUsers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
