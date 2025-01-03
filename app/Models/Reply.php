<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'user_id', 'text'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            $comment = $reply->comment; // Assumes a relationship exists
            if ($reply->user_id === $comment->user_id) {
                $reply->seen = true;
            } else {
                $reply->seen = false;
            }
        });
    }
}
