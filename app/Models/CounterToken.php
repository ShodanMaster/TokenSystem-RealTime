<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function counter(){
        return $this->belongsTo(Counter::class);
    }
}
