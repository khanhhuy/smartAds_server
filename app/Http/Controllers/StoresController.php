<?php namespace App\Http\Controllers;

use App\Http\Requests;

class StoresController extends Controller
{

    public function updateTaxonomy()
    {
//        if (Setting::get('taxonomy.updated_at') !== 'Updating') {
//            Queue::push(new UpdateTaxonomy());
//            Setting::set('taxonomy.updated_at', 'Updating');
//            Setting::save();
//            return "OK";
//        } else {
//            return "Updating";
//        }
    }

    public function updateTaxonomyStatus()
    {
//        return Setting::get('taxonomy.updated_at');
    }

}
