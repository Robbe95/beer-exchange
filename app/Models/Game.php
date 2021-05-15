<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'description',
        'title',
        'image',
    ];

    public function setPublic() {
        $this->public = true;
        $this->save();
    }

    public function setPrivate() {
        $this->public = false;
        $this->save();
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'game_genre', 'game_id', 'genre_id');
    }

    public function scopePublic($query)
    {
        return $query->where('public', true);
    }
}
