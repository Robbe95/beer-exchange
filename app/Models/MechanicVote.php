<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MechanicVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'mechanic_id',
        'user_id',
        'group_id',

        'chosen'
    ];

    public function mechanic(){
        return $this->belongsTo(Mechanic::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
