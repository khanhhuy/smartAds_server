<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SystemConfigController extends Controller {

	public function getSettings() {
		//Todo: get config and show
        return view('system.settings');
	}

	public function getTools() {
		return view('system.tools');
	}
}
