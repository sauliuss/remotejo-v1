<?php

namespace App\Helpers;

use Illuminate\Http\Request;

use App\Http\Helpers\ScrapeJobs;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use App\Models\Company;
use App\Models\Job;
use App\Models\ScrapedBenefit;
use App\Models\Tool;

use App\Helpers\StackShareScrapper;
use App\Helpers\TwitterScrapper;
use App\Helpers\GlassDoorScrapper;

class StackOverflowScraper
{
	private function getBenefitType($class_name){
		if($class_name == "svg-icon iconClock"){
			return "Time";
		}

		if($class_name == "svg-icon iconStroller"){
			return "Family";
		}

		if($class_name == "svg-icon iconMoney"){
			return "Compensation";
		}

		if($class_name == "svg-icon iconHealth"){
			return "Health";
		}

		if($class_name == "svg-icon iconFitness"){
			return "Fitness, Personal Development";
		}

		if($class_name == "svg-icon iconVacation"){
			return "Vacation";
		}

		if($class_name == "svg-icon iconComputer"){
			return "Gear";
		}

		if($class_name == "svg-icon iconChair"){
			return "Work";
		}

		if($class_name == "svg-icon iconStar" || $class_name == "svg-icon iconFaceSmile" || $class_name == "svg-icon iconHeart"){
			return "Other";
		}

		if($class_name == "svg-icon iconFood"){
			return "Food";
		}

		if($class_name == "svg-icon iconSubway"){
			return "Commute";
		}
	}

	public function get(){
		$response = [];

		$urls = [];

		for($i = 1; $i<=25; $i++){
			array_push($urls, 'https://stackoverflow.com/jobs/companies?r=true&pg='.''.$i);
		}

		foreach ($urls as $url) {
			$guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 20]);
   			$client = new Client();
   			$client->setClient($guzzle_client);

			$crawler = $client->request('GET', $url);

			$crawler->filter('.fs-body2 > a')->each(function ($node) use (&$response, &$client) {

				$parent_url = 'https://stackoverflow.com';
				$link = $parent_url.''.$node->attr('href');

				$crawler = $client->request('GET', $link);

				$post = [];

				try {

				   $post['name'] = $crawler->filter('#company-name-tagline > h1')->text();

				   // Scrapes company info

				   $crawler->filter('.ba > div')->each(function ($node) use (&$crawler, &$post, &$labels) {

				   		$label_name = $node->filter('.fw-bold.fs-caption.fs-category.fc-black-400.mb0')->text();

				   		if($label_name == "Website"){
				   			$company_name = $post['name'];
			   				$company_website = $node->filter('.d-block.mb12 > a')->attr('href');
			   				$company_website = parse_url(urldecode($company_website));
			   				$company_website = parse_str($company_website['query'], $output);
			   				$post['info']['url'] = $output['redirectUrl'];
				   		}
				   		if($label_name == "Industry"){
				   			$company_industry = explode(',', trim($node->filter('.d-block.mb12')->text()));
				   			$trimmed_company_industry = array_map('trim',$company_industry);
				   			$post['info']['industry'] = $trimmed_company_industry;
				   		}
				   		elseif($label_name == "Social"){
				   			$node->filter('a')->each(function ($node) use (&$post) {
				   				$social_link = parse_url(urldecode($node->attr('href')));
				   				$social_link = parse_str($social_link['query'], $output);
				   				$social_link = $output['redirectUrl'];

				   				if(strpos($social_link, 'facebook')){
				   					$post['info']['facebook_url'] = $social_link;
				   				}
				   				elseif(strpos($social_link, 'twitter')){
				   					$post['info']['twitter_url'] = $social_link;
				   				}
				   				elseif(strpos($social_link, 'github')){
				   					$post['info']['github_url'] = $social_link;
				   				}

				   			});

				   		}

				   		$node->filter('.grid--cell')->each(function ($result) use (&$post){

				   			$label_name = $result->filter('.fw-bold.fs-caption.fs-category.fc-black-400.mb0')->text();

				   			if($label_name == "Size"){
				   				$words_to_search = ['\u2013', 'employees', ' '];
				   				$words_to_replace = ['-', '', ''];
				   				$company_size = str_replace($words_to_search, $words_to_replace, $result->filter('.d-block.mb12')->text());
				   				$company_size_numbers = explode('–', trim($company_size));
				   				if($company_size_numbers[0] > 500){
				   					$post['info']['size'] = 4;
				   				}

				   				elseif($company_size_numbers[0] < 500 && $company_size_numbers[0]> 101){
				   					$post['info']['size'] = 3;
				   				}

				   				elseif($company_size_numbers[0] < 100 && $company_size_numbers[0]> 51){
				   					$post['info']['size'] = 2;
				   				}

				   				elseif($company_size_numbers[0] < 50 && $company_size_numbers[0]> 11){
				   					$post['info']['size'] = 1;
				   				}
				   				else{
				   					$post['info']['size'] = 0;
				   				}
				   			}
				   			elseif($label_name == "Founded"){
				   				$company_founded = $result->filter('.d-block.mb12')->text();
				   				$post['info']['founding_years'] = trim($company_founded);
				   			}
				   			elseif($label_name == "Status"){
				   				$company_type = $result->filter('.d-block.mb12')->text();

				   				if(strtolower($company_type) == "private"){
				   					$post['info']['type'] = 0;
				   				}
				   				elseif(strtolower($company_type) == "public"){
				   					$post['info']['type'] = 1;
				   				}
				   				
				   			}

				   		});			

				   });

				   // Scrapes the company's benefits
				   $benefits = [];
				   	$company_benefits = $crawler->filter('.mb8 > .grid')->each(function ($node) use (&$benefits)
				   	{

				   			$benefit = [];
				   			$benefit['category'] = $this->getBenefitType($node->filter('.grid--cell.fc-black-600 > .svg-icon ')->attr('class'));

				   			$benefit['name'] = $node->filter('.grid--cell.pl8.pt2.fw-normal.fs-body2.fc-black-700')->text();

				   			array_push($benefits,  $benefit);

				   	});
				   	$post += ['benefits' => $benefits];


				   	// Scrapes the company's tech stack
				   	$company_stack = [];
				   	$company_stack_crawler = $crawler->filter('.mb16 > .post-tag')->each(function($node) use (&$company_stack){
				   		$stack = $node->text();
				   		array_push($company_stack, $stack);

				   	});
				   	$post += ['tech_stack' => $company_stack];

				   	// Get office locations

				   	if($crawler->filter('.cp-mt.js-locations')->count() > 0){
				   		$company_offices = [];
				   		$locations = $crawler->filter('.cp-mt.js-locations > .grid--cell > .grid.fd-column');
				   		$locations->filter('.grid--cell.mb16 > span > a')->each(function($node) use (&$company_offices){
				   			$location = [];
				   			$location['location'] = $node->attr('data-query');
				   			$location['lat'] = $node->attr('data-lat');
				   			$location['lon'] = $node->attr('data-lon');
				   			array_push($company_offices, $location);
				   		});
				   		$post += ['offices' => $company_offices];
				   	}

				   	$company = Company::updateOrCreate(
						['name' => ltrim($post['name'])],
						[
							'slug' => str_slug(ltrim($post['name'])),
							'url' => isset($post['info']['url']) ? ltrim($post['info']['url']) : NULL,
							'size' => isset($post['info']['size']) ? ltrim($post['info']['size']) : NULL,
							'founding_years' => isset($post['info']['founding_years']) ? ltrim($post['info']['founding_years']) : NULL,
							'type' => isset($post['info']['type']) ? ltrim($post['info']['type']) : NULL,

							'twitter' => isset($post['info']['twitter_url'])? ltrim($post['info']['twitter_url']) : NULL,
							'github' => isset($post['info']['github_url'])? ltrim($post['info']['github_url']) : NULL,
							'facebook' => isset($post['info']['facebook_url'])? ltrim($post['info']['facebook_url']) : NULL,
						]
					);

					$company_id = $company->id;

				   	// Stores benefits
				   	$benefit_ids = [];
				   	foreach ($post['benefits'] as $benefit){

				   		$benefit = ScrapedBenefit::firstOrCreate(
				   						[
				   							'name' => $benefit['name'],
				   						],
				   						[
				   							'category' => $benefit['category']
				   						]
				   					);
				   		array_push($benefit_ids, $benefit->id);
				   	}
				   	$company->scraped_benefits()->sync($benefit_ids);

				   	// Stores tools
				   	$tool_ids = [];
				   	foreach ($post['tech_stack'] as $tool){

				   		$tool = Tool::firstOrCreate(
				   						['name' => $tool],
				   						[
				   							'slug' => str_slug($tool),
				   							'type_id' => 0
				   						]
				   					);
				   		array_push($tool_ids, $tool->id);

				   	}



				   array_push($response, $post);

				   return false;
				}

				catch (\InvalidArgumentException $e) {
				    $response += [
				    				'error' => 'Error occured while scraping StackOverflow',
				    				'error_message' => $e
				    			];
				}

				sleep(mt_rand(1,3));
			});

			sleep(mt_rand(1,5));
		}

		return $response;
	}
}
