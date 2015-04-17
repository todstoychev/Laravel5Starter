<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class EditUserRequest extends Request {

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
            'username' => 'required|alpha_num|min:3|max:30',
            'password' => 'min:6|confirmed',
            'password_confirmation' => 'min:6',
            'email' => 'required|email',
            'roles' => 'required',
        ];
    }

}
