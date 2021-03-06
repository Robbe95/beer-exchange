<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function groups() {
        return $this->belongsToMany(Group::class, 'applications', 'user_id', 'group_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status', 'accepted');
    }

    public function groupsDone() {
        return $this->belongsToMany(Group::class, 'applications', 'user_id', 'group_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status', 'accepted');
    }

    public function groupsNotDone() {
        return $this->belongsToMany(Group::class, 'applications', 'user_id', 'group_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status', 'accepted');
    }

    public function applications() {
        return $this->belongsToMany(Group::class, 'applications', 'user_id', 'group_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status','requested');
    }

    public function rejected() {
        return $this->belongsToMany(Group::class, 'applications', 'user_id', 'group_id')
            ->withTimestamps()
            ->withPivot('status', 'description')
            ->where('status','rejected');
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'genre_user', 'user_id', 'genre_id');
    }

    public function mechanics() {
        return $this->belongsToMany(Mechanic::class, 'mechanic_user', 'user_id', 'mechanic_id');
    }

    public function games() {
        return $this->belongsToMany(Game::class, 'game_user', 'user_id', 'game_id')->withPivot('type')->where('type', 'owned');;
    }

    public function favoriteGames() {
        return $this->belongsToMany(Game::class, 'game_user', 'user_id', 'game_id')->withPivot('type')->where('type', 'favorite');
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'followed_users', 'followed_id', 'follower_id');
    }

    public function followed() {
        return $this->belongsToMany(User::class, 'followed_users', 'follower_id', 'followed_id');
    }

    public function hosted_groups() {
        return $this->hasMany(Group::class, 'host_id');
    }

    public function getGroupsOfFollowedAttribute() {
        return Group::whereIn('host_id', $this->followed()->pluck('followed_id'))->get();;
    }

    //Functionality
    public function isHost($group) {
        if($group !== null && !is_object($group)) {
            $group = Group::find($group);
        }
        return $group && $this->hosted_groups()->where('groups.id', $group->id)->exists();
    }

    public function isPlayer($group) {
        if($group !== null && !is_object($group)) {
            $group = Group::find($group);
        }
        return $group && $this->hosted_groups()->where('groups.id', $group->id)->exists() || $group && $this->groups()->where('groups.id', $group->id)->exists();
    }
}
