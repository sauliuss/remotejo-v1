<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolType extends Model
{
    protected $fillable = ['name','parent_id', 'slug'];

    public function tools()
    {
    	return $this->belongsToMany('App\Models\Tool');
    }
}
