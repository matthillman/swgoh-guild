<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $appends = ['gear_twelve', 'gear_eleven', 'seven_star'];
    
    function characters() { 
        return $this->hasMany(Character::class);
    }

    function getGearTwelveAttribute() {
        return $this->characters->filter(function($character) {
            return $character->gear_level == 12;
        })->count();
    }

    function getGearElevenAttribute() {
        return $this->characters->filter(function($character) {
            return $character->gear_level == 11;
        })->count();
    }
    
    function getSevenStarAttribute() {
        return $this->characters->filter(function($character) {
            return $character->rarity == 7;
        })->count();
    }
}
