<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function counters(){
        return $this->belongsToMany(Counter::class)->withPivot('last_went')->withTimestamps();
    }

}
