<?php

namespace App\Helpers;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

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

	    public function scrapeTwitterAPI($twitter_handle){
	    	$response = [];
	
	    	try{
		    	$bearer_token ='AAAAAAAAAAAAAAAAAAAAAByiPwEAAAAAzpfmtvBbn9XYozDR4bzQGaP6Gb0%3DzinfmLmmoZerNeFY8e0afnekzmMQI0cV5Egb0K1odrmlKuErDT';
		    	$endpoint = 'https://api.twitter.com/2/users/by?usernames='.$twitter_handle.'&user.fields=profile_image_url,description';

	    	  	$guzzle_client = new GuzzleClient(
	    	  		[
	    	  			'timeout' => 60,
	    	  			'connect_timeout' => 15,
	    	  			'headers' => [
	    								'authorization' => 'Bearer ' .$bearer_token
	    							]
	    			]);

	    	  	$request = $guzzle_client->request('GET', $endpoint);
	    	  	$api_response = json_decode($request->getBody());

	    	  	$data = $api_response->data;

		    	// Gets the profile's description
		    	$profile_description = $data[0]->description;

		    	// Gets profile's image src url
		    	$profile_img_source = str_replace('_normal', '', $data[0]->profile_image_url);

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



    public function scrapeTwitterProfile($company_url){
    	$response = [];

    	try{
	    	$guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
	    	$client = new Client();
	    	$client->setClient($guzzle_client);

	    	$crawler = $client->request('GET', $company_url);

	    	// Gets the profile's description
	    	$profile_description = $crawler->filter('.css-901oao .r-18jsvk2 .r-1qd0xha .r-a023e6 .r-16dba41 .r-rjixqe .r-bcqeeo .r-qvutc0')->text();

	    	// Gets profile's image src url
	    	$profile_img_source = $crawler->filter('.css-1dbjc4n > .css-9pa8cd')->attr('src');

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
