<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReligionResource;
use App\Models\Religion;
use Illuminate\Http\Request;

class ReligionController extends BaseApiController
{
    public function index(){
        $religions = Religion::all();
        return $this->sendResponse(ReligionResource::collection($religions), "Successfully fetched religions");
    }
}
