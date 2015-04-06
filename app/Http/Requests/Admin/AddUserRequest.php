<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AddUserRequest extends Request {

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
            'username' => 'required|alpha_num|min:3|max:30|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'roles' => 'required'
        ];
    }

}
