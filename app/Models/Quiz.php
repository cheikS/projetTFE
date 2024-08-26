<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['video_id', 'title', 'description'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
