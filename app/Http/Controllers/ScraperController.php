<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use App\Models\Company;
use App\Models\Industry;
use App\Models\Job;
use App\Models\Benefit;
use App\Models\JobCategory;
use App\Models\Tool;
use App\Models\Type;
use App\Models\Rating;

use App\Helpers\WeWorkRemotelyScraper;
use App\Helpers\StackOverflowScraper;
use App\Helpers\StackShareScraper;
use App\Helpers\TwitterScraper;
use App\Helpers\GlassDoorScraper;
use App\Helpers\RemoteOkScraper;

class ScraperController extends Controller
{

	public function scrapeWeWorkRemotely(){

		$scraper = new WeWorkRemotelyScraper();
		$response = $scraper->get();

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeRemoteOK(){

		$scraper = new RemoteOkScraper();
		$response = $scraper->get();

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeStackOverflow(){

		$scraper = new StackOverflowScraper();
		$response = $scraper->get();

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeRatingsOnGlassDoor(){
		$response = [];

		// Gets the latest company's id with a rating. Gets all companies with larger id that the given.
		$latest_rating = Company::latest()->first();
		$company_id = $latest_rating->company_id;
		$companies = Company::all()->where('id', '>', $company_id );

		foreach ($companies as $company) {

			$company_name = $company->name;
			$company_url = $company->url;
			$company_id = $company->id;

			$glassdoor = new GlassdoorScraper($company_name, $company_url);
			$glassdoor_result = $glassdoor->scrapeRatings();

			if(isset($glassdoor_result['status'])){
				if($glassdoor_result['status'] == 200){
					$rating = Rating::firstOrCreate(['company_id' => $company_id],
						[
							'total_rating' => isset($glassdoor_result['response']['rating']['total_rating']) ? $glassdoor_result['response']['rating']['total_rating'] : NULL,
							'company_culture' => isset($glassdoor_result['response']['rating']['company_culture']) ? $glassdoor_result['response']['rating']['company_culture'] : NULL,
							'work_life_balance' => isset($glassdoor_result['response']['rating']['work_life_balance']) ? $glassdoor_result['response']['rating']['work_life_balance'] : NULL,
							'pay_and_benefit' => isset($glassdoor_result['response']['rating']['pay_and_benefit']) ? $glassdoor_result['response']['rating']['pay_and_benefit'] : NULL,
							'management' => isset($glassdoor_result['response']['rating']['management']) ? $glassdoor_result['response']['rating']['management'] : NULL,
							'career_opportunities' => isset($glassdoor_result['response']['rating']['career_opportunities']) ? $glassdoor_result['response']['rating']['career_opportunities'] : NULL,
							'user_id' => 1
						]);
				}

				array_push($response, $glassdoor_result);
				sleep(mt_rand(1,5));
			}

			else{
				array_push($response, $glassdoor_result);
			}
		}

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeCompanyOnGlassDoor(){
		$response = [];

		$companies = Company::whereNull('founding_years')->orWhereNull(['headquaters', 'type', 'size'])->get();

		foreach ($companies as $company) {

			$company_name = $company->name;
			$company_url = $company->url;

			$glassdoor = new GlassdoorScraper($company_name, $company_url);
			$glassdoor_result = $glassdoor->scrapeCompanyInfo();

			if(isset($glassdoor_result['status'])){
				if($glassdoor_result['status'] == 200){

					if(isset($glassdoor_result['response']['size'])){
						$company->size = $glassdoor_result['response']['size'];
					}

					if(isset($glassdoor_result['response']['founding_years'])){
						$company->founding_years = $glassdoor_result['response']['founding_years'];
					}

					if(isset($glassdoor_result['response']['headquaters'])){
						$company->headquaters = $glassdoor_result['response']['headquaters'];
					}

					if(isset($glassdoor_result['response']['type'])){
						$company->type = $glassdoor_result['response']['type'];
					}

					if(isset($glassdoor_result['response']['industry'])){
						$industry = $glassdoor_result['response']['industry'];
						$industry = Industry::firstOrCreate(['name' => $industry]);
						$company->industries()->sync($industry->id);
					}

					$company->save();

					array_push($response, $glassdoor_result);
					sleep(mt_rand(1,5));
				}
			}
			else{
				array_push($response, $glassdoor_result);
			}
		}

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeToolsOnStackShare(){
		$response = [];
		$companies = Company::whereRaw('updated_at <= now() - interval 3 minute')->get();

		foreach ($companies as $company) {
			$company_name = $company->name;

			$stackshare_scrapper = new StackShareScraper();
			$stackshare_result = $stackshare_scrapper->scrapeToolsByCompanyName($company_name);

			// Stores tools
			$tool_ids = [];

			if($stackshare_result['status'] == 200){
				foreach ($stackshare_result['response'] as $item){

					if(isset($item['category'])){
						$type = Type::firstOrCreate(
							['name' => $item['category']],
							['slug' => str_slug($item['category'])]
						);
					}

					$tool = Tool::updateOrCreate(
					    ['name' => $item['name']],
					    [
					    	'logo' => $item['logo'],
					    	'slug' => str_slug($item['name']),
					    	'type_id' => $type->id
					    ]
					);
					array_push($tool_ids, $tool->id);
				}

				$company->tools()->sync($tool_ids);
			}

			array_push($response, $stackshare_result);

			sleep(mt_rand(1,3));
		}

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeToolOnStackShare(){
		$response = [];

		$tools = Tool::whereNull('logo')->oldest('updated_at')->get();

		foreach($tools as $tool){

			$stackshare_scraper = new StackShareScraper();
			$result = $stackshare_scraper->scrapeToolByName($tool->name);

			if($result['status'] == 200){
				$tool->logo = $result['response']['logo'];

				$type = Type::firstOrCreate(
					['name' => $result['response']['category']],
					['slug' => str_slug($result['response']['category'])]
				);
				$tool->types()->sync($type->id);
				$tool->save();
			}

			array_push($response, $result);

			sleep(mt_rand(1,3));
		}
		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeCompanyOnStackShare(){
		$response = [];

		$companies = Company::whereNull('founding_years')->orWhereNull(['headquaters', 'type', 'size'])->get();

		foreach ($companies as $company) {

			$company_name = $company->name;

			$stackshare = new StackShareScraper();
			$stackshare_result = $stackshare->scrapeCompanyInfo($company_name);

			if(isset($stackshare_result['status'])){
				if($stackshare_result['status'] == 200){

					if(isset($stackshare_result['response']['industry'])){

						$industries = $stackshare_result['response']['industry'];

						foreach ($industries as $industry){
							$industry = Industry::firstOrCreate(['name' => $industry]);
							$company->industries()->sync($industry->id);
						}
					}

					$company->save();

					array_push($response, $stackshare_result);
					sleep(mt_rand(1,5));
				}
			}
			else{
				array_push($response, $stackshare_result);
			}
		}

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeTwitterUrl(){
		$response = [];
		$companies = Company::where('twitter', '=', NULL)->whereNotNull('url')->get();

		foreach($companies as $company){
			$company_id = $company->id;
			$company_url = $company->url;

			$scrapper = new TwitterScraper();
			$scrapper_response = $scrapper->scrapeTwitterUrl($company_url);

		   	$company = Company::updateOrCreate(
				['id' => $company_id],
				[
					'twitter' => isset($scrapper_response['twitter']) ? $scrapper_response['twitter'] : "Not Found"
				]
			);

			array_push($response, $scrapper_response);

			sleep(mt_rand(1,4));
		}
		return response()->json($companies, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeTwitterAPI(){
		$response = [];

		$companies = Company::whereNull('logo')->where('twitter','!=', 'Not Found')->get();

		foreach($companies as $company){
			$twitter_url = $company->twitter;

			$twitter_handle = str_replace('/', '', parse_url($twitter_url, PHP_URL_PATH));

			$scrapper = new TwitterScraper();
			$scrapper_response = $scrapper->scrapeTwitterAPI($twitter_handle);


		   	$company = Company::updateOrCreate(
				['twitter' => $twitter_url],
				[
					'description_short' => isset($scrapper_response['description']) ? $scrapper_response['description'] : '',
					'logo' => isset($scrapper_response['logo']) ? $scrapper_response['logo'] : NULL
				]
			);

			array_push($response, $scrapper_response);

			sleep(mt_rand(1,4));
		}

		array_push($response, $scrapper_response);

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function scrapeTwitterProfile(){
		$response = [];

		$companies = Company::whereNull('logo')->where('twitter','!=', 'Not Found')->get();

		foreach($companies as $company){
			$twitter_url = $company->twitter;
			$scrapper = new TwitterScraper();
			$scrapper_response = $scrapper->scrapeTwitterProfile($twitter_url);

		   	$company = Company::updateOrCreate(
				['twitter' => $twitter_url],
				[
					'description_short' => isset($scrapper_response['description']) ? $scrapper_response['description'] : '',
					'logo' => isset($scrapper_response['logo']) ? $scrapper_response['logo'] : NULL
				]
			);

			array_push($response, $scrapper_response);

			sleep(mt_rand(1,4));
		}

		array_push($response, $scrapper_response);

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}

	public function findTools(){
		$tools = Tool::all();

		$jobs = Job::all();

		$tools_in_jobs = [];

		foreach($jobs as $job){

			$job_post = $job->job_post_text;

			foreach ($tools as $tool){
				$tool_name = $tool->name;

				$tool_name_captialized = ucwords($tool_name);

				if(strpos($job_post, $tool_name)){
					array_push($tools_in_jobs, $tool_name);

					$tool_tag = '<span class="tool_tag">'.$tool_name.'</span>';

					$new_job_post = str_replace($tool_name, $tool_tag, $job_post);

					$job->job_post_text = $new_job_post;

					$job->save(); 

				}

				if(strpos($job_post, $tool_name_captialized)){
					array_push($tools_in_jobs, $tool_name);

					$tool_tag = '<span class="tool_tag">'.$tool_name_captialized.'</span>';

					$new_job_post = str_replace($tool_name, $tool_tag, $job_post);

					$job->job_post_text = $new_job_post;

					$job->save(); 
				}

			}
		}

		$response = "Done";

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}
}
