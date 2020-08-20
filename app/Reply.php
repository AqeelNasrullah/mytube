<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'body', 'comment_id', 'user_id'
    ];

    /**
     * Database Relations
     */

    // Reply belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Reply belongs to comment
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    // Replay has many likes
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Reply has many dislikes
    public function dislikes()
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }
}
