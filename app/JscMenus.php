<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JscMenus extends Model
{
    protected $guarded = [];

    public function menuItems(){
        return $this->hasMany(JscMenuItems::class,'menu_id');
    }
}
