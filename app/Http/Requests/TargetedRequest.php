<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class TargetedRequest extends Request {

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
		return [
            'is_whole_system' => 'boolean',
            'targetsID'=>'required_without:is_whole_system|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date',

            'from_age' => 'integer|min:0',
            'to_age' => 'integer|min:0',
            'gender' => 'required',
            'from_family_members' => 'integer|min:0',
            'to_family_members' => 'integer|min:0',

            'title' => 'required|min:3',
            'image_display' => 'required|boolean',
            'provide_image_link' =>'required_if:image_display,true|boolean',
            'image_file'=>'image',
            'image_url' => 'url',
            'web_url' => 'required_if:image_display,false|url',
            'auto_thumbnail'=>'boolean',
            'provide_thumbnail_link'=>'required_without:auto_thumbnail|boolean',
            'thumbnail_file'=>'image',
            'thumbnail_url' => 'url',
		];
	}

}
