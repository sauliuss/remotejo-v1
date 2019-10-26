<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiringRegion extends Model
{
    protected $fillable = ['name','parent_ids', 'emoji', 'code', 'unicode', 'slug'];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_ids' => 'array',
    ];

	public function companies()
    {
    	return $this->belongsToMany('App\Models\Company');
    }

    public function jobs()
    {
    	return $this->belongsToMany('App\Models\Job');
    }


}
