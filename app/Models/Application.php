<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'status',
        'description',
        'user_id',
        'group_id'
    ];


    public function setStatus($status) {
        $this->status = $status;
    }

    public function user() {
        return $this->hasOne(User::class);
    }

    public function group() {
        return $this->hasOne(Group::class);
    }
}
