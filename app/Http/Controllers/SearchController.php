<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tool;
use App\Models\Type;

class SearchController extends Controller
{
	public function __construct(){}

    public function searchTools($keyword){
    	$response = [];
    	$tools = Tool::where('name', 'like', "%{$keyword}%")->get();

    	foreach($tools as $tool){
    		$result = [
    					'id' => $tool->id,
    					'name' => $tool->name,
    					'logo' => $tool->logo,    				];

    		array_push($response, $result);
    	}

    	return response()->json($response, 200,[],JSON_PRETTY_PRINT);

    }
}
