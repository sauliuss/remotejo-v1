<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Company as Company;
use App\Models\Tool;
use App\Models\Job;
use App\Models\Benefit;
use App\Models\Industry;
use App\Models\HiringRegion;

use App\Enums\RemoteLevel;
use App\Enums\CompanySize;
use App\Enums\CompanyType;

use Image;
use Illuminate\Support\Facades\Storage;

class CompaniesController extends Controller
{
    public function showAll(){
    	$companies  = Company::with(['tools.types', 'jobs', 'benefits', 'hiring_regions','industries'])->oldest()->paginate(5);
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
    										'regions' => $regions->whereIn('type', ['country', 'continent'])->values()
    									],
    				'industries' => Industry::all(),
    				'benefits' => $benefits,
    				'remote_level' => RemoteLevel::toSelectArray(),
    				'size' => CompanySize::toSelectArray(),
    				'type' => CompanyType::toSelectArray()
    			];





    	return view('welcome')->with(['companies' => $companies, 'options' => $options]);
    }

    public function showCompany($id){
        $company  = Company::with(['tools', 'jobs', 'benefits', 'hiring_regions','industries'])->find($id);


        return view('company')->with(['company' => $company]);
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
}
