<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Mmanos\Search\Search;

abstract class Controller extends BaseController {

    use DispatchesCommands,
        ValidatesRequests;
    
    public function __construct() {
        $this->middleware('last_activity');
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
     * @param $admin_template Admin email template
     * 
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
     * Renders the all items page
     * 
     * @param Illuminate\Database\Eloquent\Model $query Initial query
     * @param string $uri URI (base path prefix)
     * @param string $title Page title
     * @param string $view View to render
     * @param string $delete_message Delete confirmation dialog message text
     * @param integer $limit Items per page
     * @param string $param Column name to sort
     * @param string $order Order to sort
     * @return Response
     */
    protected function all($request, $query, $cache_key, $uri, $title, $delete_message, $view) {
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
     * @param string $index Search index
     * @return Array
     */
    protected function search($search, $index) {
        $search_instance = new Search();
        $result = $search_instance->index($index)
                ->search(null, $search)
                ->get();
        
        $array = $this->formIdsArray($result);
        
        return $array;
    }
    
    /**
     * Fomr the ids array
     * 
     * @param Array $result Full text search result
     * @return Array
     */
    private function formIdsArray($result) {
        $array = [];
        
        foreach ($result as $item) {
            $array[] = $item['id'];
        }
        
        return $array;
    }

    /**
     * Creates params array
     * 
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    private function formParams(\Illuminate\Http\Request $request) {
        $params = [];

        $params['limit'] = $request->input('limit') ? $request->input('limit') : null;
        $params['order'] = $request->input('order') ? $request->input('order') : null;
        $params['param'] = $request->input('param') ? $request->input('param') : null;

        return $params;
    }

}
