<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    function members() { 
        return $this->hasMany(Member::class);
    }
}
