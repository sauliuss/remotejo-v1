<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['total_rating', 'company_culture', 'work_life_balance', 'pay_and_benefit', 'management', 'career_opportunities', 'rating_source', 'company_id', 'user_id'];

    public function company(){
    	return $this->belongsTo('App\Models\Company');
    }
}
