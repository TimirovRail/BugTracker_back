<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentAttachment extends Model
{
    protected $fillable = ['path', 'comment_id'];

    // Связь с комментарием
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
