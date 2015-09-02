<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Nqxcode\LuceneSearch\Facade as Search;

/**
 * Base controller class. Holds some basic methods used by the child classes
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Http\Controllers
 */
abstract class Controller extends BaseController {

    use DispatchesCommands,
        ValidatesRequests;

    public function __construct() {
        $this->middleware('last_activity');
        $this->middleware('permissions');
        $this->middleware('locale');
    }

    /**
     * Function to send emails to the user and to the admin.
     * The input array must be in the following format
     * [
     *  'user_email' => '',
     *  'username' => '',
     *  'user_subject' => '',
     *  'admin_subject' => '',
     *  'user_email_text' => '',
     *  'admin_mail_text' =>
     * ]
     *
     * @param Array $data Data to use for the emails
     * @param string $user_template User email template
     * @param string $admin_template Admin email template
     * @return boolean
     */
    protected function sendMail($data, $user_template, $admin_template) {

        // Send user email
        Mail::send($user_template, $data, function($msg) use ($data) {
            $msg->to($data['user_email'], $data['username']);
            $msg->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
            $msg->subject($data['user_subject']);
        });

        // Check for email failures
        if (count(Mail::failures()) > 0) {
            return false;
        }

        // Send admin email
        Mail::send($admin_template, $data, function($msg) use ($data) {
            $msg->to(Config::get('mail.from.address'), Config::get('mail.from.name'));
            $msg->from($data['user_email'], $data['username']);
            $msg->subject($data['admin_subject']);
        });

        // Check for email failures
        if (count(Mail::failures()) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Handles the all items pages
     *
     * @param Request $request
     * @param mixed $query
     * @param string $uri
     * @param string $title
     * @param string $delete_message
     * @param string $view
     * @return \Illuminate\View\View
     */
    protected function all(
        Request $request,
        $query,
        $uri,
        $title,
        $delete_message,
        $view
    ) {
        $params = $this->formParams($request);

        $results = $query;

        // Order
        if ($params['param'] && $params['order']) {
            $results = $results->orderBy($params['param'], $params['order']);
        }

        $paginator = $results->paginate($params['limit']);
        $paginator->appends(['limit' => $params['limit'], 'param' => $params['param'], 'order' => $params['order']]);
        $paginator->setPath('all');

        $data = [
            'results' => $paginator,
            'uri' => $uri,
            'title' => $title,
            'delete_message' => $delete_message,
            'limit' => $params['limit'],
            'param' => $params['param'],
            'order' => $params['order']
        ];

        return view($view, $data);
    }

    /**
     * Handles full text search
     *
     * @param string $search Search string
     * @param array $options
     * @return Array
     */
    protected function search($search, $options = []) {
        $result = Search::query($search, '*', $options)->get();
        $array = $this->formIdsArray($result);

        return $array;
    }

    /**
     * Form the ids array
     *
     * @param Array $result Full text search result
     * @return Array
     */
    protected function formIdsArray($result) {
        $array = [];

        foreach ($result as $item) {
            $array[] = $item['id'];
        }

        return $array;
    }

    /**
     * Creates params array
     *
     * @param Request|\Illuminate\Http\Request $request
     * @return Array
     */
    protected function formParams(Request $request) {
        $params = [];

        $params['limit'] = $request->input('limit') ? $request->input('limit') : null;
        $params['order'] = $request->input('order') ? $request->input('order') : null;
        $params['param'] = $request->input('param') ? $request->input('param') : null;

        return $params;
    }

}
