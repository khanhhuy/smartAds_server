<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdsController extends Controller {

    public function show($ads)
    {
        return view('ads.'.$ads->id,compact('ads'));
	}

}
