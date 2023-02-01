<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class EndpointController extends Controller
{

    public function index(){
        return response()->json('success');
    }


}
