<?php namespace App\Http\Requests;

use Route;

class MinorRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        if ($this->isStoreRequest()) {
            return [
                'minor_id' => 'required|unique:beacon_minors,minor',
            ];
        }
        // } else {
        //     $minor = Route::input('minor_id');
        //     return [
        //         'minor_id' => 'required|unique:beacon_minors,minor,' . $minor->minor . ',minor',
        //     ];
        // }
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return redirect('minors/errors')->withInput($this->except($this->dontFlash))->withErrors($errors);
    }

    private function isStoreRequest()
    {
        return Request::isMethod('POST');
    }

}
