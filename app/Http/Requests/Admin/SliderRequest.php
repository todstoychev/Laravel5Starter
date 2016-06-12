<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Models\Settings;

class SliderRequest extends Request
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
            'image' => 'required|image',
            'link' => 'url',
            'image_name' => 'required|min:5|alpha_dash'
        ];
        $i = 0;

        foreach (Settings::getLocales() as $locale) {
            $rules['title.' . $i] = 'min:5';
            $rules['text.' . $i] = 'min:20';
            $i++;
        }

        if ($this->route('one')) {
            $rules['image'] = 'image';
        }

        return $rules;
    }
}
