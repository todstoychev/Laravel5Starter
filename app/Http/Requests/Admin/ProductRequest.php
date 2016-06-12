<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ProductRequest extends Request
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
            'qty' => 'required|numeric|min:0',
            'price' => 'required|float',
            'currency' => 'required|currency',
            'description' => 'required',
            'title' => 'required',
            'main_image' => 'required|image',
//            'product_images' => 'required'
        ];

        foreach ($this->input('title') as $locale => $title) {
            $rules['title.' . $locale] = 'required|min:5';
            $rules['description.' . $locale] = 'required|min:20';
        }

//        foreach ($this->files->get('product_images') as $key => $image) {
//            $rules['product_images.' . $key] = ($this->route('one')) ? 'image' : 'required|image';
//        }

        if ($this->route('one')) {
            $rules['main_image'] = 'image';
            $rules['product_images'] = '';
        }

        return $rules;
    }
}
