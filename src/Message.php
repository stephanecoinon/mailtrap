<?php

namespace StephaneCoinon\Mailtrap;

class Message extends Model
{
    /**
     * Get the message recipient e-mails as an array
     *
     * @return array
     */
    public function recipientEmails()
    {
        return array_map(function ($email) {
            return trim($email);
        }, explode(',', $this->to_email));
    }

    /**
     * Get the message subject
     *
     * @return string       The email subject
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * Get the message body
     *
     * @return string   The email body
     */
    public function body()
    {
        $fullPathParts = explode("/", $this->html_path);
        $partialPath = join("/", array_slice($fullPathParts, 3));
        $this->body = (new Model())->get($partialPath)->getAttributes()[0];
        return $this->body;
    }


}
