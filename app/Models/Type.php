<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['name','parent_id', 'slug'];

    public function tools()
    {
    	return $this->hasMany('App\Models\Tool');
    }
}
