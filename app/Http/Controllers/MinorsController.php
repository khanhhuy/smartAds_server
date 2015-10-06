<?php namespace App\Http\Controllers;

use App\Http\Requests;

class MinorsController extends Controller
{

    public function manage()
    {
        return view('minors.manage');
    }

}
