<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catergory extends Model
{
    protected $fillable=['name'];
    use HasFactory;

   /**
    * Get all of the posts for the Catergory
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function posts(): HasMany
   {
       return $this->hasMany(Post::class);
   }
}
