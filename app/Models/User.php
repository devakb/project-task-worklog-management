<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $appends = ['avatar'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_senior_member',
        'password',
        'status',
    ];

    const SELECT_ROLE_TYPES = [
        1 => 'Senior Member',
        0 => "Junior Member"
    ];
    const SELECT_STATUS_TYPES = [
        'active' => 'Active',
        'inactive' => "Inactive"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    const AVATARURL = "https://api.dicebear.com/9.x/initials/svg?scale=80&seed=";
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function scopeSearchNameEmail($query, String $value){
        return $query->where(function($query) use ($value){
            $query->where('name', 'LIKE', '%'.$value.'%')
            ->orWhere('email', 'LIKE', '%'.$value.'%');
        });
    }

    public function getAvatarAttribute(){

        return static::AVATARURL . urlencode($this->name);
    }

    public function getAvatar(){

        $url = static::AVATARURL;

        if($this->name){
            return $url . urlencode(str_replace(["."], "", $this->name));
        }

        return asset("images/avatar-98g7w34.png");

    }



}
