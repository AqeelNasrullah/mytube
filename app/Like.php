<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id', 'likeable_id', 'likeable_type'
    ];

    /**
     * Database Relations
     */

    // Like belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Like belongs to video, comment, reply
    public function likeable()
    {
        return $this->morphTo();
    }
}
