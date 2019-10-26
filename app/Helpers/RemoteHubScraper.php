<?php

namespace App\Helpers;

use Illuminate\Http\Request;

use App\Http\Helpers\ScrapeJobs;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use App\Models\Company;
use App\Models\Job;

class RemoteHubScraper
{
	public function get(){
		$response = [];

		$guzzle_client = new GuzzleClient(['timeout' => 600]);
		$company_link_scraper = new Client();
		$company_link_scraper->setClient($guzzle_client);

		$crawler = $company_link_scraper->request('GET', 'https://remotehub.io/remote-companies');

		$crawler->filter('.list__item > a')->each(function($node) use(&$guzzle_client, &$response){
			$company = [];
			$benefits = [];
			$tools = [];
			$tags =[];

			$base_url = 'https://remotehub.io';
			$company_page_url = $node->attr('href');
			$company_page_url = $base_url.''.$company_page_url;

			$company_data_scraper = new Client();
			$company_data_scraper->setClient($guzzle_client);

			$crawler = $company_data_scraper->request('GET', $company_page_url);

			$company['name'] = $crawler->filter('.list__h2')->text();
			$company['short_description'] = trim($crawler->filter('.list__tagline')->text());

			if($crawler->filter('.container__2-3.container--left > .block.mb-30 > .block__item.block__item--text')->count()){
				$company['full_descritpion'] = trim($crawler->filter('.container__2-3.container--left > .block.mb-30 > .block__item.block__item--text')->html());
			}

			$crawler->filter('.list__info > .tag.pull-left')->each(function ($node) use(&$company){
				$size = $node->text();

				if(strpos($size, 'members')){

					$size_number = str_replace('members', '', $size);
					$company += ['size' => trim($size_number)];
				}
			});

			// $crawler->filter('.block.mb-30')->children('.block__item.block__item--link')->each(function ($node) use(&$benefits, &$tools){

			// 	$scraped_url = $node->extract(['href']);

			// 	// Evaluates if benefits block is crawled by comparing a href value with the begining of Benefit page url.
			// 	if(strpos($scraped_url[0], 'remote-companies-with-')){
			// 		$benefit = $node->text();
			// 		array_push($benefits, trim($benefit));
			// 	}
			// 	elseif(strpos($scraped_url[0], '/remote-tools')){
			// 		$tool = $node->text();
			// 		array_push($tools, trim($tool));
			// 	}
			// });

			$crawler->filter('.container__1-3.container--right')->children('.title__small')->each(function ($node) use(&$tags, &$benefits, &$tools, &$company){
				$section_title = $node->text();

				if($section_title == "Benefits"){
					$section = $node->nextAll()->eq(0);

					$section->filter('.block__item.block__item--link')->each(function ($node) use(&$benefits){
						$item = $node->text();
						array_push($benefits, trim($item));
					});
				}
				elseif($section_title == "Remote tools"){
					$section = $node->nextAll()->eq(0);

					$section->filter('.block__item.block__item--link')->each(function ($node) use(&$tools){
						$item = $node->text();
						array_push($tools, trim($item));
					});
				}

				elseif($section_title == "About"){
					$section = $node->nextAll()->eq(0);

					$section->filter('.tag.tag--link.mb-5')->each(function ($node) use(&$tags){
						$item = $node->text();
						array_push($tags, trim($item));
					});
				}

				elseif($section_title == "Links"){
					$section = $node->nextAll()->eq(0);

					$section->filter('.tag.tag--link')->each(function ($node) use(&$company){
						$item = $node->extract(['href']);

						if(strpos($item[0], 'twitter')){
							$company += ['twitter' => $item];
						}
						else{
							$company += ['url' => $item];
						}
					});
				}
			});
			$company += ['tools' => $tools];
			$company += ['benefits' => $benefits];
			$company += ['tags' => $tags];


			array_push($response, $company);

		});

		return response()->json($response, 200,[],JSON_PRETTY_PRINT);
	}
}
