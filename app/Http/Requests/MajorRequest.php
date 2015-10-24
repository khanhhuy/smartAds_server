<?php namespace App\Http\Requests;

use Route;

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
        if ($this->isStoreRequest()) {
            return [
                'major' => 'required|unique:beacon_majors,major',
                'store_id' => 'required|unique:beacon_majors,store_id'
            ];
        } else {
            $major = Route::input('majors');

            return [
                'major' => 'required|unique:beacon_majors,major,' . $major->major . ',major',
                'store_id' => 'required|unique:beacon_majors,store_id,' . $major->store_id . ',store_id'
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
        if ($this->isStoreRequest()) {
            return redirect()->route('majors.create')
                ->withInput($this->except($this->dontFlash))
                ->withErrors($errors, $this->errorBag);
        } else {
            $major = Route::input('majors');
            return redirect()->route('majors.edit',$major->major)
                ->withInput($this->except($this->dontFlash))
                ->withErrors($errors, $this->errorBag);
        }

    }

    private function isStoreRequest()
    {
        return Request::isMethod('POST');
    }

}
