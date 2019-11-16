<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
	protected $fillable = ['name', 'description', 'parent_id', 'type_id', 'made_by', 'logo', 'slug'];
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

	public function type()
    {
    	return $this->belongsTo('App\Models\Type');
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
