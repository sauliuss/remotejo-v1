<?php

namespace App\Helpers;

use Illuminate\Http\Request;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

class WeWorkRemotelyScraper
{
	public function get(){
		$response = [];
		$urls = [
			'https://weworkremotely.com/categories/remote-programming-jobs/#job-listings',
			'https://weworkremotely.com/categories/remote-design-jobs/#job-listings',
			'https://weworkremotely.com/categories/remote-copywriting-jobs/#job-listings',
			'https://weworkremotely.com/categories/remote-devops-sysadmin-jobs/#job-listings',
			'https://weworkremotely.com/categories/business-and-management/#job-listings',
			'https://weworkremotely.com/categories/product/#job-listings',
			'https://weworkremotely.com/categories/remote-customer-support-jobs/#job-listings',
			'https://weworkremotely.com/categories/finance-and-legal/#job-listings',
			'https://weworkremotely.com/categories/sales-and-marketing/#job-listings',
			'https://weworkremotely.com/categories/remote-jobs/#job-listings',
		];

		$guzzle_client = new GuzzleClient(['timeout' => 600, 'connect_timeout' => 15]);
		$client = new Client();
		$client->setClient($guzzle_client);

		foreach ($urls as $url) {
   			// Loads a category page 
			$crawler = $client->request('GET', $url, []);

			// Filters job posts in a category page
			$crawler->filter('article>ul>li>a')->each(function ($node) use (&$response, &$client) {

				$parent_url = 'https://weworkremotely.com';
				$link = $parent_url.''.$node->attr('href');

				// Loads a job post page
				$crawler = $client->request('GET', $link);

				try {
					// Checks if a job post's type is a contract or not
					if($crawler->filter('.listing-header-container > a > .listing-tag')->text() == "Contract"){

						$job_type = $crawler->filter('.listing-header-container > a > .listing-tag')->eq(0)->count() ?
						$crawler->filter('.listing-header-container > a > .listing-tag')->eq(0)->text() : "";

						$job_category = $crawler->filter('.listing-header-container > a > .listing-tag')->eq(1)->count() ?
						$crawler->filter('.listing-header-container > a > .listing-tag')->eq(1)->text() : "";

						$job_hiring_timezones = $crawler->filter('.listing-header-container > .listing-tag')->eq(0)->count() ?
						$crawler->filter('.listing-header-container > .listing-tag')->eq(0)->text() : "";
					}

					else{
						$job_type = $crawler->filter('.listing-header-container > .listing-tag')->eq(0)->count() ?
						$crawler->filter('.listing-header-container > .listing-tag')->eq(0)->text() : "";

						$job_category = $crawler->filter('.listing-header-container > a > .listing-tag')->eq(0)->count() ?
						$crawler->filter('.listing-header-container > a > .listing-tag')->eq(0)->text() : "";

						$job_hiring_timezones = $crawler->filter('.listing-header-container > .listing-tag')->eq(1)->count() ?
						$crawler->filter('.listing-header-container > .listing-tag')->eq(1)->text() : "";

					}

					$job_title = $crawler->filter('.listing-header-container > h1')->text();

					$job_post = $crawler->filter('.listing-container')->html();

					$job_post_text = $crawler->filter('.listing-container')->text();


					$company_name = $crawler->filter('.company-card > h2 > a')->text();

					$company_hq = $crawler->filter('.company-card > h3')->eq(0)->text();

					$company_link = $crawler->filter('.company-card > h3 > a')->eq(0)->attr('href');

					// Parses a given url for its base part. Because sometimes urls might be with different paths. That results in multple company records atlhough it's the same company.
					$company_link = parse_url($company_link);
					$company_url = $company_link['scheme'].'://'.$company_link['host'];

					$company = Company::firstOrCreate(
						['name' => ltrim($company_name)],
						[
							'url' => ltrim($company_url),
							'headquaters' => ltrim($company_hq),
						]
					);

					$job = Job::firstOrCreate(
						[
							'job_title' => ltrim($job_title),
							'company_id' => $company->id,
						],
						[
							'company_id' => $company->id,
							'job_post' => $job_post,
							'job_post_text' => $job_post_text,
							'job_type' => ltrim($job_type),
							'job_hiring_timezones' => ltrim($job_hiring_timezones),
							'job_source' => ltrim($link),
						]
					);

					$job_category = Category::firstOrCreate([
						'name' =>ltrim($job_category)
					])->id;

					$job->categories()->sync($job_category);

				   $post = [
				   			'type' => $job_type,
				   			'category' => $job_category,
				   			'hiring_region' => $job_hiring_timezones,
				   			'title' => $job_title,
				   			'company' => [
				   							'name' => trim($company_name),
				   							'url' => $company_url,
				   							'id' => $company->id,
				   						]
				   ];

				   array_push($response, $post);

				   return false;
				}

				catch (\InvalidArgumentException $e) {
				    $response += [
				    				'error' => 'Error occured while scraping WeWorkRemotely',
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
