<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catergory extends Model
{
    protected $fillable=['name'];
    use HasFactory;


   public function posts(): HasMany
   {
       return $this->hasMany(Post::class);
   }

   //morphMany between categories and images
   public function images()
   {
       return $this->morphMany(Image::class, 'imageable');
   }

}
