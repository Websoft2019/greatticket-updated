<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class PageController extends BaseApiController
{
    public function termCondition(){
        $term = TermsCondition::first();
        return $this->sendResponse($term, "Terms and Condition fetched successfully",200);
    }

    public function privacy(){
        $privacy = PrivacyPolicy::first();
        return $this->sendResponse($privacy,"Privacy policy fetched successfully",200);
    }
}
