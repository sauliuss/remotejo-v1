<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Job;

class JobsController extends Controller
{
    public function showJob($slug){

        $job  = Job::where('job_slug', $slug)->with(['company'])->get();


        // $meta_data = [
        //             'company_size' => CompanySize::getDescription($company[0]->size),
        //             'company_type' => CompanyType::getDescription($company[0]->type),
        //             'remote_level' => RemoteLevel::getDescription($company[0]->remote_level),
        //             'company_timezones' => $company[0]->hiring_regions->pluck('id')->toArray(),
        //             'timezones' => HiringRegion::where('type', 'timezone')->get(),
        //             'company_url_host' => preg_replace('#^www\.(.+\.)#i', '$1',parse_url($company[0]->url, PHP_URL_HOST)),
        //             'company_tools' => $tools,
        //             'job_categories' => JobCategory::all(),
        //             'notification' => JobCategory::where('id','>',2)->get(),
        //         ];

        return view('job')->with(['job' => $job]);

    }
}
