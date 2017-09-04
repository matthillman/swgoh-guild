<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    function characters() { 
        return $this->hasMany(Character::class);
    }
}
