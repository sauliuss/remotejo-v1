<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Enums\RemoteLevel;
use App\Enums\CompanySize;
use App\Enums\CompanyType;

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
            'is_verified' => $this->is_verified,
            'description_short' => $this->description_short,
            'founding_years' => $this->founding_years,
            'headquaters' => $this->headquaters,
            'remote_level' => $this->remote_level,
            'remote_level_name' => RemoteLevel::getDescription($this->remote_level),
            'size' => $this->size,
            'size_alias' => CompanySize::getDescription($this->size),
            'type' => $this->type,
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'github' => $this->github,            

            'industries' => $this->industries,
            'tools' => $this->tools,
            'jobs' => $this->jobs,
            'benefits' => $this->benefits,
            'hiring_regions' => $this->hiring_regions,

        ];
    }
}
