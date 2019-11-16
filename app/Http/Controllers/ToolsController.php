<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tool;

class ToolsController extends Controller
{
    public function showTool($slug){
        $tool  = Tool::where('slug', $slug)->get();
        
        return view('tool')->with(['data' => $tool]);
    }
}
