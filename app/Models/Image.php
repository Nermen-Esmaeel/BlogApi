<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable=['url'];

    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class , 'post_id', 'id');
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
