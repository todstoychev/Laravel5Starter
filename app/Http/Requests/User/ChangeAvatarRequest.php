<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class ChangeAvatarRequest extends Request {

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
        Session::put('profile_tab', 'avatar');
        return [
            'avatar' => 'required|image'
        ];
    }

}
