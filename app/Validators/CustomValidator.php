<?php namespace App\Validators;

use Auth;
use Illuminate\Validation\Validator;

/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 7/25/2015
 * Time: 5:08 PM
 */
class CustomValidator extends Validator
{
    public function validateCurrentPassword($attribute, $value, $parameters)
    {
        if (Auth::guest()) {
            return false;
        }
        return Auth::validate(['email' => Auth::user()->email, 'password' => $value]);
    }
}