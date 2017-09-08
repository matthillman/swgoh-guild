<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $appends = ['member_name'];
    
    function member() {
    	return $this->belongsTo(Member::class);
    }

    function getMemberNameAttribute() {
        return $this->member->name;
    }
}
