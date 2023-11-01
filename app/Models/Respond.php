<?php

namespace App\Models;

use App\Models\Responder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Respond extends Model
{
    use HasFactory;

    public function responder()
    {
        return $this->belongsTo(Responder::class);
    }
}
