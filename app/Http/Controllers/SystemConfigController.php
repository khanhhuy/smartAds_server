<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Setting;

class SystemConfigController extends Controller
{

    public function getSettings()
    {
        //Todo: get config and show
        return view('system.settings');
    }

    public function getTools()
    {
        $names = ['taxonomy', 'stores_areas'];
        foreach ($names as $name) {
            $lastUpdated[$name] = Setting::get("$name.updated_at");
            $updating[$name] = ($lastUpdated[$name] === 'Updating');
        }
        return view('system.tools')->with(compact('lastUpdated', 'updating', 'names'));
    }

}
