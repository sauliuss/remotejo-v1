<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Enums\RemoteLevel;
use App\Enums\CompanySize;
use App\Enums\CompanyType;

use App\Models\HiringRegion;
use App\Models\JobCategory;
use App\Models\Tool;

class ToolsController extends Controller
{
    public function showTool($slug){
        $tool  = Tool::with(['type', 'companies.industries',])->where('slug', $slug)->get();
        return view('tool')->with(['data' => $tool]);
    }
}
