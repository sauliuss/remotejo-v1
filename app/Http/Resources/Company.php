<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Enums\RemoteLevel;
use App\Enums\CompanySize;
use App\Enums\CompanyType;
use App\Models\JobCategory;

class Company extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'url' => $this->url,
            'url_host' => preg_replace('#^www\.(.+\.)#i', '$1',parse_url($this->url, PHP_URL_HOST)),
            'logo' => $this->logo,
            'is_claimed' => $this->is_claimed,
            'description_short' => $this->description_short,
            'description_full' => $this->description_full,
            'founding_years' => $this->founding_years,
            'headquaters' => $this->headquaters,
            'remote_level' => $this->remote_level,
            'remote_level_name' => RemoteLevel::getDescription($this->remote_level),
            'size' => $this->size,
            'size_alias' => CompanySize::getDescription($this->size),
            'type' => $this->type,
            'type_alias' => CompanyType::getDescription($this->type),
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'github' => $this->github,            
            'industries' => $this->industries,
            'tools' => $this->tools->groupBy('type_id'),
            'jobs' => $this->jobs,
            'job_categories' => JobCategory::all(),
            'benefits' => [ 
                            'health' => $this->when($this->benefits->where('parent_id', '=', '1')->count() > 0, $this->benefits->where('parent_id', '=', '1')),
                            'compensation' => $this->when($this->benefits->where('parent_id', '=', '7')->count() > 0, $this->benefits->where('parent_id', '=', '7')),
                            'timeoff' => $this->when($this->benefits->where('parent_id', '=', '12')->count() > 0, $this->benefits->where('parent_id', '=', '12')),
                            'other' => $this->when($this->benefits->where('parent_id', '=', '19')->count() > 0, $this->benefits->where('parent_id', '=', '19')),
                        ],
            'timezones' => $this->hiring_regions->where('type', 'timezone'),
            'regions' => $this->hiring_regions->where('type','!=', 'timezone'),
            'hiring_regions' => $this->hiring_regions,
            'updated_at' => $this->updated_at->diffForHumans()

        ];
    }
}
