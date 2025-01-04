<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'user_id'];

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

        static::creating(function ($commentLike) {
            $comment = $commentLike->comment; // Assumes a relationship exists
            if ($commentLike->user_id === $comment->user_id) {
                $commentLike->seen = true;
            } else {
                $commentLike->seen = false;
            }
        });
    }
    
}
