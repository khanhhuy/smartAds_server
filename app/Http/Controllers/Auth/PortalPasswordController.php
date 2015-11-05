<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeInfoRequest;
use Auth;
use Lang;
use Laracasts\Flash\Flash;
use Utils;

class PortalPasswordController extends Controller
{


    public function __construct()
    {
    }

    public function adminEdit()
    {
        return view('auth.password.admin-edit');
    }

    public function managerEdit()
    {
        return view('auth.password.manager-edit');
    }

    public function update(ChangeInfoRequest $request)
    {
        Auth::user()->update(['password' => bcrypt($request->input('new_password'))]);
        Flash::overlay(Lang::get('flash.change_pass_success'), 'Success!');
        return redirect(Utils::getCurrentUserHome());
    }

}
