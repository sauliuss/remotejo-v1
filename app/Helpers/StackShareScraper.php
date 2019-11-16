<?php

namespace App\Helpers;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;

use Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Tool;
use App\Models\Type;
use App\Models\Company;

class StackShareScraper
{
  /**
    * Scrape the stack when a company name is given
    *
    * @param  string  $company
    * @return Array
    */

  private function downloadImage($source){
    $image = file_get_contents($source);
    return $image;
  }

  public function scrapeToolByName($tool_name){
    $response = [];

    $tool_name = str_slug($tool_name);
    $url = 'https://stackshare.io/'.$tool_name;

    $guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
    $client = new Client();
    $client->setClient($guzzle_client);

    try{
      $tool_page = $client->request('GET', $url);

      // Checks for a tool title. If it exists, continues.
      if($tool_page->filter('.css-1jrtzo8')->count()){

          // Gets the tool's category
          $tool_category = $tool_page->filter('.css-ld8qhm')->eq(1)->text();

          // Gets the tool's image src url
          $image_source = $tool_page->filter('.css-kryi8h')->extract(['src']);

          // Downloads the image
          $new_image = $this->downloadImage($image_source[0]);

          // Gets the image extension (i.e. png, jpg, gif)
          $image_extension = pathinfo($image_source[0], PATHINFO_EXTENSION);

          $new_name = str_slug($tool_page->filter('.css-1jrtzo8')->text());
          
          // Creates a new image name
          $new_image_name = ''.$new_name.'.'.$image_extension;

          // Creates the path where to save a new image
          $new_path = 'public/logos/'.$new_image_name;

          // Saves a new image
          $new_image = Storage::put($new_path, $new_image);

          $response += [
                'status' => 200,
                'response' => [
                                'category' => $tool_category,
                                'logo' => 'logos/'.$new_image_name
                              ]
          ];
      }
      else{
        $result = "Tool was not found";
        $response +=  [
                        'status' => 404,
                        'response' => $count
                      ];
      }

    }
    catch(\Exception $e){
      $response +=  [
                      'status' => 500,
                      'response' => ['error' => $e]
                    ];
    }
    return $response;
  }

	public function scrapeToolsByCompanyName($company_name){

		$response = [];

		$company_name = $company_name;

		// Converts a company name to a slug for creating valid urls.
		$company_slug = str_slug($company_name);

		$base_url = 'https://stackshare.io/';
		$stack_url = $base_url.''.str_slug($company_slug).'/'.str_slug($company_slug);

		$guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
		$client = new Client();
		$client->setClient($guzzle_client);

		// Sends GET requests. Catch errors
		try{
			$stack_page = $client->request('GET', $stack_url);

        // Validates if the stack page exist
        // If filter() returns 0 results, 404 not found
        if($stack_page->filter('.css-180cglb')->count() <= 0){
          $result = "Stack page of $company_name was not found";
          $response += [
                'status' => 404,
                'response' => ['error' => $result]
            ];
        }
        else{
          $stack = [];
          $stack_page->filter('.css-180cglb')->each(function($node) use (&$stack){
            $category_name = $node->filter('.css-1lu1fqf')->text();

            $node->filter('.css-1k0l9wu')->each(function($node) use(&$stack, &$category_name){

              // Gets the stack's image src url
              $image_source = $node->filter('.css-eei33z')->extract(['src']);

              // Downloads the image
              $new_image = $this->downloadImage($image_source[0]);

              // Gets the image extension (i.e. png, jpg, gif)
              $image_extension = pathinfo($image_source[0], PATHINFO_EXTENSION);

              $new_name = str_slug($node->filter('.css-12fxiad')->text());
              
              // Creates a new image name
              // $new_image_name = ''.hash('sha256', time()).'.'.$image_extension;
              $new_image_name = ''.$new_name.'.'.$image_extension;

              // Creates the path where to save a new image
              $new_path = 'public/logos/'.$new_image_name;

              // Saves a new image
              $new_image = Storage::put($new_path, $new_image);


              $tool = [
                    'category' => $category_name,
                    'name' => $node->filter('.css-12fxiad')->text(),
                    'logo' => 'logos/'.$new_image_name
                  ];

              array_push($stack, $tool);
            });
          });

          $response += [
                'status' => 200,
                'response' => $stack
          ];
        }
		}
    catch (\InvalidArgumentException $e) {
			$result = "Guzzle error: $e";
      $response += [
            'status' => 500,
            'response' => ['error' => $result]
        ];
		}
		return $response;
	}

  public function scrapeCompanyInfo($company_name){
    $response = [];

    $company_name = $company_name;

    // Converts a company name to a slug for creating valid urls.
    $company_slug = str_slug($company_name);

    $base_url = 'https://stackshare.io/';
    $company_url = $base_url.''.str_slug($company_slug);

    $guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
    $client = new Client();
    $client->setClient($guzzle_client);

    // Sends GET requests. Catch errors
    try{
      $company_page = $client->request('GET', $company_url);

      // Validates if the stack page exist
      // If filter() returns 0 results, 404 not found
      if($stack_page->filter('.css-180cglb')->count() <= 0){
        $result = "Company page of $company_name was not found: $url";
        $response += [
              'status' => 404,
              'response' => ['error' => $result]
          ];
      }
      else{
        $result = [];

        // Filters company's page url
        if($company_page->filter('.company-link')->count() > 0){
          $result['url'] = $company_page->filter('.company-link')->extract(['href']);
        }

        // Filters company's tags
        if($company_page->filter('.tags')->children('.btn.btn-ss-g.btn-xs')->count() > 0){
         $industries = [];
         $company_page->filter('.tags')->children('.btn.btn-ss-g.btn-xs')->each(function($node) use(&$tags) {
           array_push($industries, $node->text());
         });
         $result['industry'] = $tags;
        }

        // Filters company's social media urls
        if($company_page->filter('.social-links-co.hidden-xs > a')->count() > 0){
          $company_page->filter('.social-links-co.hidden-xs > a')->each(function ($node) use(&$result){
            $item = $node->extract(['href']);

            if(strpos($item[0], 'twitter')){
              $result += ['twitter' => $item];
            }
            elseif(strpos($item[0], 'angel')){
              $result += ['angellist' => $item];
            }
          });
        }
        $response += [
              'status' => 200,
              'response' => $result
            ];
      }
    }
    catch (\Exception $e) {
      $result = "Guzzle error: $e";
      $response += [
            'status' => 500,
            'response' => ['error' => $result]
        ];
    }
    return $response;
  }


}
