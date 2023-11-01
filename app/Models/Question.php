<?php

namespace App\Models;

use App\Models\Set;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
}
