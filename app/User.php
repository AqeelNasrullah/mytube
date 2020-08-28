<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'role_id', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Database Relations
     */

    // User belongs to role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // User has one channel
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    // User belongs to many channels
    public function manyChannels()
    {
        return $this->belongsToMany(Channel::class)->withTimestamps();
    }

    // User has many videos
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    // User has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // User has many replies
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // User belongs to many videos
    public function manyVideos()
    {
        return $this->belongsToMany(Video::class)->withTimestamps();
    }

    // User has many likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // User has many dislikes
    public function disLikes()
    {
        return $this->hasMany(Dislike::class);
    }

    // User has many verify emails
    public function verifyEmails()
    {
        return $this->hasMany(VerifyEmail::class);
    }
}
