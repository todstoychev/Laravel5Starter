<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class ChangePasswordRequest extends Request {

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
        // Put session active tab
        Session::put('profile_tab', 'password');
        
        return [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6'
        ];
    }

}
