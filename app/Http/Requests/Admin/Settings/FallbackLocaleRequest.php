<?php

namespace App\Http\Requests\Admin\Settings;

use App\Http\Requests\Request;

class FallbackLocaleRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'fallback_locale' => 'required|size:2'
        ];
    }

}
