<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contacts\ContactsFormRequest;
use App\Http\Requests;
use App\Services\SendMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ContactsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex()
    {
        return view('contacts.contacts');
    }

    public function postSendEmail(ContactsFormRequest $request)
    {
        $sendMail = new SendMail();
        $data = [
            'mail_to' => Config::get('mail.from.address'),
            'recipient_name' => Config::get('mail.from.name'),
            'mail_from' => $request->input('from'),
            'sender_name' => (Auth::user()) ? Auth::user()->username : null,
            'subject' => $request->input('subject'),
            'body' => $request->input('message')
        ];

        $response = $sendMail->sendMail('emails.contacts', $data);

        if (false === $response) {
            flash()->error(trans('contacts.send_error'));
        } else {
            flash()->success(trans('contacts.send_success'));
        }

        return redirect('contacts/index');
    }
}
