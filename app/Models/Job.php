<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['job_title', 'job_post', 'job_post_text', 'job_type', 'job_slug', 'job_category', 'job_tags', 'job_compensation', 'job_source', 'company_id'];

    public function company(){
    	return $this->belongsTo('App\Models\Company');
    }

    public function hiring_regions()
    {
        return $this->belongsToMany('App\Models\HiringRegion');
    }

    public function categories(){
        return $this->belongsToMany('App\Models\JobCategory');
    }

    public function tools(){
    	return $this->belongsToMany('App\Models\Tool');
    }

    public function benefits(){
    	return $this->belongsToMany('App\Models\Benefit');
    }


}
