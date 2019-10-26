<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = ['name','parent_id','slug'];

    public function jobs()
    {
    	return $this->belongsToMany('App\Models\Job');
    }
}
