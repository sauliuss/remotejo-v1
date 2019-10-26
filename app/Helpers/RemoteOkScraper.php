<?php

namespace App\Helpers;

use Illuminate\Http\Request;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use App\Models\Company;
use App\Models\Job;

class RemoteOkScraper
{
	public function get(){

		$response = [];

		$urls = [
			'https://remoteok.io/remote-jobs',
			'https://remoteok.io/remote-dev-jobs',
			'https://remoteok.io/remote-customer-support-jobs',
			'https://remoteok.io/remote-marketing-jobs',
			'https://remoteok.io/remote-design-jobs',
			'https://remoteok.io/remote-non-tech-jobs',
		];

		foreach ($urls as $url) {

			try{
				$guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 20]);
	   			$client = new Client();
	   			$client->setClient($guzzle_client);
				$crawler = $client->request('GET', $url);

				$crawler->filter('#jobsboard>.job')->each(function ($node) use (&$response, &$client) {

					$parent_url = 'https://remoteok.io';
					$link = $parent_url.''.$node->attr('data-url');

					$crawler = $client->request('GET', $link);

					try {
						$job_title = $crawler->filter('.company > h2')->text();

						$job_post = $crawler->filter('.expandContents')->html();

						$company_name = $crawler->filter('.company > .companyLink > h3')->text();


						$company = Company::firstOrCreate(
							['name' => ltrim($company_name)]
						);


						$job = Job::firstOrCreate(
							[
								'job_title' => ltrim($job_title),
								'company_id' => $company->id,
							],
							[
								'company_id' => $company->id,
								'job_post' => $job_post,
								'job_source' => $link,
							]
						);


					   $post = [
					   			'title' => $job_title,
					   			'job_post' => $job_post,
					   			'company_name' => $company_name,
					   ];

					   array_push($response, $post);

					   return false;
					}

					catch (\Exception $e) {
					    $result = "Error happened while scraping the page: $e";
					    $response += [
					          'status' => 500,
					          'response' => ['error' => $result]
					      ];
					}

					sleep(mt_rand(1,2));
				});

			}

			catch (\Exception $e) {
			    $result = "Guzzle error: $e";
			    $response += [
			          'status' => 500,
			          'response' => ['error' => $result]
			      ];
			}

			sleep(mt_rand(1,3));
		}

		return $response;

	}
}
