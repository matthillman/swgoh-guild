<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    function member() {
    	return $this->belongsTo(Character::class);
    }
}
