<?php

namespace App\Helpers;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use Image;
use Illuminate\Support\Facades\Storage;

class TwitterScraper
{

	public function scrapeTwitterUrl($company_url){
		$response = [];

		try{
			$guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
			$client = new Client();
			$client->setClient($guzzle_client);

			try{
				$crawler = $client->request('GET', $company_url);

				// Finds all links in a given page
				$crawler->filter('a')->each(function($node) use (&$response){
					$link = $node->attr('href');

					// Returns a link if it contains 'twitter' and a given company name
					if(strpos($link, 'twitter')){
						$response['twitter'] = $link;
					}
					else{
						array_push($response, $link);
					}

				});

			}catch (\GuzzleHttp\Exception\ConnectException $e) {
				$response += [
								'error' => "Error occured while scraping $company_url",
								'error_message' => $e
							];
			}
		}
		catch (\GuzzleHttp\Exception\RequestException | \GuzzleHttp\Exception\ConnectException $e) {
			$response += [
							'error' => "Error occured while scraping $company_url",
							'error_message' => $e
						];
		}

		return $response;
	}



    public function scrapeTwitterProfile($company_url){
    	$response = [];

    	try{
	    	$guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
	    	$client = new Client();
	    	$client->setClient($guzzle_client);

	    	$crawler = $client->request('GET', $company_url);

	    	// Gets the profile's description
	    	$profile_description = $crawler->filter('.ProfileHeaderCard-bio.u-dir')->text();

	    	// Gets profile's image src url
	    	$profile_img_source = $crawler->filter('.ProfileAvatar-image')->attr('src');

	        // Gets the image extension (i.e. png, jpg, gif)
			$image_extension = pathinfo($profile_img_source, PATHINFO_EXTENSION);

			// Creates a new image name
			$new_image_name = ''.hash('sha256', time()).'.'.$image_extension;

	        // Creates the path where to save a new image
			$new_path = 'public/companies/'.$new_image_name;
			
			// Downloads the image
			$new_image = file_get_contents($profile_img_source);

	        // Saves a new image
	   		$new_image = Storage::put($new_path, $new_image);

	   		$response += [
	   						'logo' => 'companies/'.$new_image_name,
	   						'description' => ltrim($profile_description)
	   					];
    	}

    	catch (\InvalidArgumentException $e) {
    		$response += [
    						'error' => 'Error occured while scraping Twitter',
    						'error_message' => $e
    					];
    	}

   		return $response;
    }
}
