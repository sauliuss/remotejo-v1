<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = ['name', 'parent_id', 'slug'];
    protected $hidden = ['created_at', 'updated_at'];

    public function companies()
    {
    	return $this->belongsToMany('App\Models\Company');
    }

    public function jobs()
    {
    	return $this->belongsToMany('App\Models\Job');
    }
}
