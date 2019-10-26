<?php

namespace App\Helpers;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;


class GlassDoorScraper
{
     /**
        * Scrape the stack when a company name is given
        *
        * @param  string  $company
        * @return Array
        */

     public $name;
     public $url;

	public function __construct($company_name, $company_url)
	{
		$this->name = $company_name;
    $this->url = $company_url;
	}

  public function scrapeRatings(){
    $response = [];
    $response_status = '';

    $company_name = str_slug($this->name, '-');
    $company_name_length = strlen($company_name);
    $url = 'https://www.glassdoor.com/Reviews/'.$company_name.'-reviews-SRCH_KE0,'.$company_name_length.'.htm';

    $client = new Client();
    $guzzle_client = new GuzzleClient(['timeout' => 60, 'connect_timeout' => 15]);
    $client->setClient($guzzle_client);

    try{
      $crawler = $client->request('GET', $url);

      if($crawler->filter('#SearchSuggestions')->count()){
        $result = "Given url does not match with a company's url on Glassdoor. Search link: $url";
        $response += [
              'status' => 404,
              'response' => ['error' => $result]
          ];
      }
      else{
        $company_url = $this->url;

        $glassdoor_company_url = $crawler->filter('.webInfo>.url')->eq(0)->text();

        // Check if the given company's url matches Glassdoor url to make sure it's the same company
        if(strpos($company_url, $glassdoor_company_url)){
          $result = $crawler->filter('.tightAll.h2')->each(function ($node) use(&$response, &$client){
            try{
              $link = $node->link();
              $gd_company_page = $client->click($link);
              $gd_company_page_url = $client->getHistory()->current()->getUri();

              // Get the company's ID
              $gd_company_id = $gd_company_page->filter('#EmpHero')->attr('data-employer-id');

              // Get the company's ratings from json file
              $gd_ratings_url = 'https://www.glassdoor.com/api/employer/'.$gd_company_id.'-rating.htm';
              $crawler_gd_ratings = file_get_contents($gd_ratings_url);
              $crawler_gd_ratings = json_decode($crawler_gd_ratings)->ratings;

              // Get the company's ratings
              // Using foreach instead of each() because $crawler_gd_ratings is JSON and not an object.
              $gd_ratings = [];
              foreach ($crawler_gd_ratings as $item) {
                if($item->type == 'overallRating' && $item->hasRating == true){
                  $gd_ratings += ['total_rating' => $item->value];
                }
                elseif($item->type == 'cultureAndValues' && $item->hasRating == true){
                  $gd_ratings += ['company_culture' => $item->value];
                }
                elseif($item->type == 'workLife' && $item->hasRating == true){
                  $gd_ratings += ['work_life_balance' => $item->value];
                }
                elseif($item->type == 'compAndBenefits' && $item->hasRating == true){
                  $gd_ratings += ['pay_and_benefit' => $item->value];
                }
                elseif($item->type == 'seniorManagement' && $item->hasRating == true){
                  $gd_ratings['management'] = $item->value;
                }
                elseif($item->type == 'careerOpportunities' && $item->hasRating == true){
                  $gd_ratings += ['career_opportunities' => $item->value];
                }
              }

              $response += [
                    'status' => 200,
                    'response' => ['rating' => $gd_ratings]
                ];

            }catch (\InvalidArgumentException $e){
              $result = 'Glassdoor Page Error: '.$e;
              $response += [
                    'status' => 500,
                    'response' => ['error' => $result]
                ];
            }
          });
        }
        else{
          $result = 'No results was found on Glassdoor. Search link:'.$url;
          $response += [
                'status' => 404,
                'response' => ['error' => $result]
            ];
        }
      }
    }

    catch (\InvalidArgumentException $e) {
      $result = 'Glassdoor Error: '.$url;
      $response += [
            'status' => 500,
            'response' => ['error' => $result]
        ];
    }
    return $response;
  }

	public function scrapeCompanyInfo(){
    $response = [];

    $company_name = str_slug($this->name, '-');
    $company_url = $this->url;
    $company_name_length = strlen($company_name);
    $url = 'https://www.glassdoor.com/Reviews/'.$company_name.'-reviews-SRCH_KE0,'.$company_name_length.'.htm';

    $client = new Client();
    $guzzle_client = new GuzzleClient(['timeout' => 45, 'connect_timeout' => 15]);
    $client->setClient($guzzle_client);

    try{
      $crawler = $client->request('GET', $url);

      if($crawler->filter('#SearchSuggestions')->count()){
        $result = "Given url does not match with a company's url on Glassdoor. Search link: $url";
        $response += [
              'status' => 404,
              'response' => ['error' => $result]
          ];
      }
      else{

        // Check if the given page contains any results
        if($crawler->filter('.webInfo > .url')->eq(0)->count() > 0){
          $glassdoor_company_url = $crawler->filter('.webInfo > .url')->eq(0)->text();

          // Check if the given company's url matches Glassdoor url to make sure it's the same company
          if(strpos($company_url, $glassdoor_company_url)){
            $result = $crawler->filter('.tightAll.h2')->each(function ($node) use(&$response, &$client){
              try{
                $link = $node->link();
                $gd_company_page = $client->click($link);
                $gd_company_page_url = $client->getHistory()->current()->getUri();

                // Retrieves and filters info about the given company
                $gd_company_info = [];
                $gd_company_page->filter('.infoEntity')->each(function ($node) use (&$gd_company_info) {

                  $label = $node->filter('label')->eq(0)->text();
                  $info = $node->filter('.value')->eq(0)->text();

                  if($label == 'Size'){
                    // Convert scrapped "1 to 50 employees" string to "1-50"
                    $words_to_search = ['to', 'employees', 'employee', ' '];
                    $words_to_replace = ['-', '', '', ''];
                    $info = str_replace($words_to_search, $words_to_replace, $info);
                    if(trim($info) == "Unknown"){
                      $gd_company_info += ['size' => NULL];
                    }
                    else{
                      $gd_company_info += ['size' => trim($info)];
                    }
                  }
                  elseif($label == 'Headquarters'){
                    if(trim($info) == "Unknown"){
                      $gd_company_info += ['headquates' => NULL];
                    }
                    else{
                      $gd_company_info += ['headquaters' => trim($info)];
                    }
                  }
                  elseif($label == 'Type'){
                    $info = str_replace('Company - ', '', $info);

                    if(trim($info) == "Unknown"){
                      $gd_company_info += ['type' => NULL];
                    }
                    else{
                      $gd_company_info += ['type' => trim($info)];
                    }
                  }
                  elseif($label == 'Industry'){
                    if(trim($info) == "Unknown"){
                      $gd_company_info += ['industry' => NULL];
                    }
                    else{
                      $gd_company_info += ['industry' => trim($info)];
                    }
                  }
                  elseif($label == 'Founded'){
                    if(trim($info) == "Unknown"){
                      $gd_company_info += ['founding_years' => NULL];
                    }
                    else{
                      $gd_company_info += ['founding_years' => trim($info)];
                    }
                  }
                  elseif($label == 'Website'){
                    $info = $node->filter('a')->eq(0)->attr('href');
                    $gd_company_info += ['url' => trim($info)];
                  }
                });

                $response += [
                      'status' => 200,
                      'response' => $gd_company_info,
                  ];
              }
              catch (\InvalidArgumentException $e){
                $result = 'Glassdoor Page Error: '.$e;
                $response += [
                      'status' => 500,
                      'response' => ['error' => $result]
                  ];
              }
            });
          }
          else{
            $result = 'No company with a given url was found on Glassdoor. Search link:'.$url;
            $response += [
                  'status' => 404,
                  'response' => ['error' => $result]
              ];
          }
        }
        else{
          $result = 'No results was found on Glassdoor. Search link:'.$url;
          $response += [
                'status' => 404,
                'response' => ['error' => $result]
            ];
        }
      }
    }
    catch (\GuzzleHttp\Exception\RequestException | \GuzzleHttp\Exception\ConnectException $e) {
      $result = 'Guzzle error: '.$e;
      $response += [
            'status' => 500,
            'response' => ['error' => $result]
        ];
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      $result = 'Guzzle error: '.$e;
      $response += [
            'status' => 500,
            'response' => ['error' => $result]
        ];
    }
    return $response;
	}
}
