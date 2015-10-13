<?php namespace App\Http\Requests;

class PromotionRequest extends Request
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
        $rules = [
            'itemsID' => 'required|array',
            'is_whole_system' => 'boolean',
            'targetsID'=>'required_without:is_whole_system|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount_value' => 'required|numeric|min:0.001',
            'discount_rate' => 'required|numeric|between:0.01,100',
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
        return $rules;
    }

}
