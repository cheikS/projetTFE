<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [      
        'title', 'description', 'instructor_id', 'duration', 'level', 'language', 'price', 'category'
    ];
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    
}
