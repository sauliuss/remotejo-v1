<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Company as Company;
use App\Models\Tool;
use App\Models\Type;
use App\Models\Job;
use App\Models\Benefit;
use App\Models\Industry;
use App\Models\HiringRegion;
use App\Models\JobCategory;

use App\Enums\RemoteLevel;
use App\Enums\CompanySize;
use App\Enums\CompanyType;

use Image;
use Illuminate\Support\Facades\Storage;

use DB;

class CompaniesController extends Controller
{
    public function showAll(){
    	$companies  = Company::with(['tools', 'jobs', 'benefits', 'hiring_regions','industries'])->oldest()->paginate(5);
    	$regions = HiringRegion::all();

    	$benefits = [];
    	$benefits_categories = Benefit::where('parent_id',NULL)->get();

    	foreach ($benefits_categories as $category) {
    		$category_id = $category->id;
    		$category_benefits = Benefit::where('parent_id',$category_id)->get();

    		array_push($benefits, [
    						'category' => $category->name,
    						'benefits' => $category_benefits
    					]);
    	}

    	$options = [
    				'hiring_regions' => [
    										'timezones' => $regions->where('type', 'timezone')->values(),
    										'countries' => $regions->where('type', 'country')->values(),
    										'continents' => $regions->where('type', 'continent')->values(),
    										'regions' => $regions->whereIn('type', ['country', 'continent'])->values(),
    									],
    				'industries' => Industry::all(),
    				'benefits' => $benefits,
    				'remote_level' => RemoteLevel::toSelectArray(),
    				'size' => CompanySize::toSelectArray(),
    				'type' => CompanyType::toSelectArray(),
    			];





    	return view('welcome')->with(['companies' => $companies, 'options' => $options]);
    }

    public function showCompany($slug){;
        $company  = Company::where('slug', $slug)->with(['tools', 'jobs', 'benefits', 'hiring_regions','industries'])->get();

        $tools = $company[0]->tools->groupBy('type_id');


        $meta_data = [
                    'company_size' => CompanySize::getDescription($company[0]->size),
                    'company_type' => CompanyType::getDescription($company[0]->type),
                    'remote_level' => RemoteLevel::getDescription($company[0]->remote_level),
                    'company_timezones' => $company[0]->hiring_regions->pluck('id')->toArray(),
                    'timezones' => HiringRegion::where('type', 'timezone')->get(),
                    'company_url_host' => preg_replace('#^www\.(.+\.)#i', '$1',parse_url($company[0]->url, PHP_URL_HOST)),
                    'company_tools' => $tools,
                    'job_categories' => JobCategory::all(),
                    'notification' => JobCategory::where('id','>',2)->get(),
                ];

        return view('company')->with(['data' => $company, 'meta_data' =>  $meta_data]);
    }

    public function showCompanyById($id){
        // $company  = Company::with(['tools.types', 'jobs', 'benefits', 'hiring_regions','industries'])->find($id);
        $company  = Company::where('id', $id)->with([ 'jobs', 'tools.type', 'benefits', 'hiring_regions','industries'])->get();

        $tools = $company[0]->tools->groupBy('type_id');



        $meta_data = [
                    'company_size' => CompanySize::getDescription($company[0]->size),
                    'company_type' => CompanyType::getDescription($company[0]->type),
                    'remote_level' => RemoteLevel::getDescription($company[0]->remote_level),
                    'company_timezones' => $company[0]->hiring_regions->pluck('id')->toArray(),
                    'timezones' => HiringRegion::where('type', 'timezone')->get(),
                    'company_url_host' => preg_replace('#^www\.(.+\.)#i', '$1',parse_url($company[0]->url, PHP_URL_HOST)),
                    'company_tools' => $tools,
                    'job_categories' => JobCategory::all(),
                    'notification' => JobCategory::where('id','>',2)->get(),
                ];

        return view('company')->with(['data' => $company, 'meta_data' =>  $meta_data]);
    }

    public function store(Request $request){

    	// $this->validate($request,
    	//     [
    	//         'name' => 'required|unique:companies,name',
    	//         'url' => 'required|url',
    	//     ]
    	// );


    	//TODO: If image field/object is not empty - update/upload a new image. Else do nothing/leave the old image

    	$company = Company::find($request->get('id'));
    	$company->name = $request->get('name');
    	$company->description_short = $request->get('description_short');
    	$company->url = $request->get('url');
    	$company->founding_years = $request->get('founding_years');
    	$company->headquaters = $request->get('headquaters');
    	$company->remote_level = $request->get('remote_level');
    	$company->size = $request->get('size');
    	$company->type = $request->get('type');
    	$company->twitter = $request->get('twitter');
    	$company->facebook = $request->get('facebook');
    	$company->github = $request->get('github');

        // Checks if the file was provided
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            
            $new_image_name = $image->hashName();

            // Creates the path where to save a new image
            $new_path = 'public/companies/'.$new_image_name;

            $image = Image::make($image)->resize(400,null, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $new_image = Storage::put($new_path, (string) $image->encode());
            $company->logo = 'companies/'.$new_image_name;
        }
        elseif($request->get('logo') == null){
            $company->logo = NULL;
        }
    	$company->save();

    	$company->tools()->sync($request->get('tools'));
    	$company->benefits()->sync($request->get('benefits'));
    	$company->industries()->sync($request->get('industries'));
    	$company->hiring_regions()->sync($request->get('hiring_regions'));



		return response()->json([
		                'status'   => 'success',
		                'message' => $company->benefits()->get()

		],200);
    }

    public function showCompaniesByStack(){;

        // $company = Company::with(['tools' => function ($query) {
        //     $query->where('name', '=', 'jenkins');
        // }])->get();

        // $company = Company::whereHas('tools', function ($query) {
        //     $query->where('id', '=', '91');
        // })->whereHas('hiring_regions', function ($query) {
        //     $query->where('id', '=', '2');
        // })->get();
        $levels = [0,1,2,3];
        $tools = [91,134,1];
        $regions = [2];

       $company = Company::whereIn('remote_level', $levels)->
       whereHas('tools', function($q) use ($tools) {
           $q->whereIn('id', $tools);
       })
       ->get();


        // $company = Company::where('remote_level','=',1)->with(['tools' => function ($query) {
        //     $query->where('id', '=', 91);
        // }])->get();

       $regions = HiringRegion::all();

        $benefits = [];
        $benefits_categories = Benefit::where('parent_id',NULL)->get();

        foreach ($benefits_categories as $category) {
            $category_id = $category->id;
            $category_benefits = Benefit::where('parent_id',$category_id)->get();

            array_push($benefits, [
                            'category' => $category->name,
                            'benefits' => $category_benefits
                        ]);
        }

       $meta_data = [
                'hiring_regions' => [
                                        'timezones' => $regions->where('type', 'timezone')->values(),
                                        'countries' => $regions->where('type', 'country')->values(),
                                        'continents' => $regions->where('type', 'continent')->values(),
                                        'regions' => $regions->whereIn('type', ['country', 'continent'])->values(),
                                    ],
                'industries' => Industry::all(),
                'benefits' => $benefits,
                'remote_level' => RemoteLevel::toSelectArray(),
                'size' => CompanySize::toSelectArray(),
                'type' => CompanyType::toSelectArray(),
                'default_img' => '/img/default.svg',
            ];

        return view('companies')->with(['data' => $company, 'meta_data' => $meta_data]);
    }

    public function filter(Request $request){

        $remote_level = $request->remote_level;
        $type = $request->type;
        $size = $request->size;
        $tools = $request->tools;
        $hiring_regions = $request->hiring_regions;
        $industries = $request->industries;


        $query = Company::with(['hiring_regions', 'tools', 'industries']);

        if($request->filled('hiring_regions')){
            $query->whereHas('hiring_regions', function($q1) use ($hiring_regions) {
                $q1->whereIn('hiring_regions.id', $hiring_regions);
            });
        }

        if($request->filled('tools')){
            $query->whereHas('tools', function($q2) use ($tools) {
                $q2->whereIn('tools.id', $tools);
            });
        }

        if($request->filled('industries')){
            $query->whereHas('industries', function($q) use ($industries) {
                $q->whereIn('id', $industries);
            });
        }

        if($request->filled('size')){
            $query->whereIn('size', $size);
        }

        if($request->filled('remote_level')){
            $query->whereIn('remote_level', $remote_level);
        }

        if($request->filled('type')){
            $query->whereIn('type', $type);
        }

        $results = $query->latest()->paginate(20);
    

       // $company = Company::whereIn('remote_level', $levels)->
       // whereHas('hiring_regions', function($q) use ($regions) {
       //     $q->whereIn('id', $regions);
       // })
       // ->with(['tools','hiring_regions'])->get();


        // $company = Company::where('remote_level','=',1)->with(['tools' => function ($query) {
        //     $query->where('id', '=', 91);
        // }])->get();

       $regions = HiringRegion::all();

        $benefits = [];
        $benefits_categories = Benefit::where('parent_id',NULL)->get();

        foreach ($benefits_categories as $category) {
            $category_id = $category->id;
            $category_benefits = Benefit::where('parent_id',$category_id)->get();

            array_push($benefits, [
                            'category' => $category->name,
                            'benefits' => $category_benefits
                        ]);
        }

       $response = [
                $results,
                'meta' => [
                            'result_count' => $results->total(),
                            'hiring_regions' => [
                                            'timezones' => $regions->where('type', 'timezone')->values(),
                                            'countries' => $regions->where('type', 'country')->values(),
                                            'continents' => $regions->where('type', 'continent')->values(),
                                            'regions' => $regions->whereIn('type', ['country', 'continent'])->values(),
                                        ],
                            'industries' => Industry::all(),
                            'benefits' => $benefits,
                            'remote_level' => RemoteLevel::toSelectArray(),
                            'size' => CompanySize::toSelectArray(),
                            'type' => CompanyType::toSelectArray(),
                ]
            ];

        return response()->json([
                                'status' => 'success',
                                'response' => $response
        ],200);
    }
}
