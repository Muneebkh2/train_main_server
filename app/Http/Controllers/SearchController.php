<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Resource;
use App\Availability;
use App\Search;

class SearchController extends Controller
{
    public function __construct(){}

    public function index()
    {
        
    }

    public function filter(Request $request)
    {
        // $resource = $resource->newQuery();
        // $availability = $availability->newQuery();
        // if ($request->has('date')) {
        //     $availability->select('resource_id')->where('availability', $request->input('date'));
        //     return response()->json($availability->get());
        // }
        // if ($request->has('surname')) {
        //     $resource->where('surname', $request->input('surname'));
        // }
        // if ($request->has('postcode')) {
        //     $resource->where('postcode', $request->input('postcode'));
        // }
        // if ($request->has('location')) {
        //     $resource->where('city', $request->input('location'));
        // }
        // if ($request->has('date')) {
            
        //     $availability->where('availability', $request->input('date'));
        // }
        // if ($request->has('surname')) {
        //     $resource->where('surname', $request->input('surname'));
        // }

        // return response()->json($resource->get());
    }
}
