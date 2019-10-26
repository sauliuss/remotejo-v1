<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'description_short', 'description_full', 'headquaters', 'size', 'founding_years', 'type',  'is_claimed', 'url', 'twitter', 'github', 'producthunt', 'youtube', 'facebook', 'slug'];

    public function benefits()
    {
    	return $this->belongsToMany('App\Models\Benefit');
    }

    public function scraped_benefits()
    {
        return $this->belongsToMany('App\Models\ScrapedBenefit');
    }

    public function hiring_regions()
    {
        return $this->belongsToMany('App\Models\HiringRegion');
    }

    public function tools()
    {
    	return $this->belongsToMany('App\Models\Tool');
    }

    public function ratings(){
        return $this->hasMany('App\Models\Job');
    }

    public function industries()
    {
    	return $this->belongsToMany('App\Models\Industry');
    }

    public function jobs(){
    	return $this->hasMany('App\Models\Job');
    }
}
