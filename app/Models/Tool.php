<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
	protected $fillable = ['name', 'description', 'made_by', 'logo', 'slug'];
    protected $hidden = ['created_at', 'updated_at'];

	public function types()
    {
    	return $this->belongsToMany('App\Models\ToolType');
    }

	public function companies()
    {
    	return $this->belongsToMany('App\Models\Company');
    }

    public function jobs()
    {
    	return $this->belongsToMany('App\Models\Job');
    }

}
