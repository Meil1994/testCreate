<?php

namespace App\Models;

use App\Models\Test;
use App\Models\Respond;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Responder extends Model
{
    use HasFactory;

    protected $fillable = [
        'status'
    ];

    public function responds()
    {
        return $this->hasMany(Respond::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
