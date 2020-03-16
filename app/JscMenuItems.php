<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JscMenuItems extends Model
{
    protected $guarded = ['id'];

    public function menu(){
        return $this->belongsTo(JscMenus::class);
    }

    public function parent(){
        return $this->belongsTo(JscMenuItems::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(JscMenuItems::class, 'parent_id');
    }
    
}
