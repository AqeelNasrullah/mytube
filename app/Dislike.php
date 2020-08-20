<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    protected $fillable = [
        'user_id', 'dislikeable_id', 'dislikeable_type'
    ];

    /**
     * Database Relations
     */

    // Dislike belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Dislike belongs to video, comment, reply
    public function dislikeable()
    {
        return $this->morphTo();
    }
}
