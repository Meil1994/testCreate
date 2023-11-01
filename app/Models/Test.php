<?php

namespace App\Models;

use App\Models\Set;
use App\Models\Question;
use App\Models\Responder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_name',
        'status',
        'passing_score'
    ];

    public function sets()
    {
        return $this->hasMany(Set::class);
    }

    public function responders()
    {
        return $this->hasMany(Responder::class);
    }
}
