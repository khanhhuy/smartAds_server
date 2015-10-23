<?php namespace App\Http\Requests;

use Route;
use App\Http\Requests\Request;

class MajorRequest extends Request
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
        if (Request::isMethod('POST')) {
            return [
                'major' => 'required|unique:beacon_majors,major',
                'store_id' => 'required|unique:beacon_majors,store_id'
            ];
        } elseif (Request::isMethod('PUT') || Request::isMethod('PATCH')) {
            $major = Route::input('majors');

            return [
                'major' => 'required|unique:beacon_majors,major,' . $major->major.',major',
                'store_id' => 'required|unique:beacon_majors,store_id,' . $major->store_id.',store_id'
            ];
        }
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return redirect()->route('majors.create')
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }

}
