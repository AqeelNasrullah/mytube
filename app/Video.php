<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'video', 'thumbnail', 'title', 'slug', 'description', 'category_id', 'user_id', 'status'
    ];

    /**
     * Database Relations
     */

    // Video belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Video belongs to category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Video has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Video has many histories
    public function histories()
    {
        return $this->hasMany(History::class);
    }

    // Video has many likes
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Video has many dislikes
    public function disLikes()
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }
}
