<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable=['user_id' , 'category_id' , 'title' , ' content' , 'slug'];


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'post_id', 'tag_id')->as('posts_tags');
    }


    //morphMany between posts and images
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }



    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class );
    }


    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class ,'user_id', 'id');
    }
}
