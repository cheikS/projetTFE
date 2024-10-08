<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','login', 'email','email_verified_at','password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    
    public function instructedCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
    

    public $timestamps = false;

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    
}
