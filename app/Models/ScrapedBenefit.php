<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedBenefit extends Model
{
    protected $fillable = ['name', 'category'];

    public function companies()
    {
    	return $this->belongsToMany('App\Models\Company');
    }

    public function jobs()
    {
    	return $this->belongsToMany('App\Models\Job');
    }
}
