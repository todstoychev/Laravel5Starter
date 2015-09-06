<?php

namespace App\Http\Requests\Contacts;

use App\Http\Requests\Request;

class ContactsFormRequest extends Request
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
        return [
            'from' => 'required|email',
            'subject' => 'required|min:3|max:255',
            'message' => 'required|min:20|max:1000'
        ];
    }
}
