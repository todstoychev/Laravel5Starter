<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

/**
 * Mail sending service for usage in the controllers
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package app\Services
 */
class SendMail
{
    /**
     * Sends an email
     *
     * @param string $template Template name
     * @param array $data Email data. Must contain
     * @example
     * [
     *  'mail_to' => <recipient email address>,
     *  'recipient_name' => <recipient_name>,
     *  'mail_from' => <sender_address>,
     *  'sender_name' => <sender_name>,
     *  'subject' => <email_subject>
     * ]
     * @return bool
     */
    public function sendMail($template, array $data)
    {
        Mail::send($template, $data, function($msg) use ($data) {
            $msg->to($data['mail_to'], $data['recipient_name']);
            $msg->from($data['mail_from'], $data['sender_name']);
            $msg->replyTo($data['mail_from'], $data['sender_name']);
            $msg->subject($data['subject']);
        });

        // Check for email failures
        if (count(Mail::failures()) > 0) {
            return false;
        }

        return true;
    }
}