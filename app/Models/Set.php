<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Set extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'instructions',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
