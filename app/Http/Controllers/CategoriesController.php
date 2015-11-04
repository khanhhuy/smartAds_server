<?php namespace App\Http\Controllers;

use App\Commands\UpdateTaxonomy;
use App\Http\Requests;
use Queue;
use Setting;

class CategoriesController extends Controller
{
    public function updateTaxonomy()
    {
        if (Setting::get('taxonomy.updated_at') !== 'Updating') {
            Queue::push(new UpdateTaxonomy());
            Setting::set('taxonomy.updated_at', 'Updating');
            Setting::save();
            return "OK";
        } else {
            return "Updating";
        }
    }

    public function updateTaxonomyStatus()
    {
        return Setting::get('taxonomy.updated_at');
    }

}
