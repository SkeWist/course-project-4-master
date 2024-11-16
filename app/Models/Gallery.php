<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['anime_id', 'image_url'];

    /**
     * Картинка принадлежит одному аниме.
     */
    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
