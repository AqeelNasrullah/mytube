<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body', 'video_id', 'user_id'
    ];

    /**
     * Database Relations
     */

    // Comment belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Comment belongs to video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Comment has many replies
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // Comments has many likes
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Comments has many dislikes
    public function disLikes()
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }
}
