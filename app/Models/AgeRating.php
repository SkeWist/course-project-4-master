<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeRating extends Model
{
    use HasFactory;
    protected $table = 'age_rating';
    protected $fillable = ['name'];

    public function anime()
    {
        return $this->hasMany(Anime::class);
    }
}
