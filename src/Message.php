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
     * Get the HTML message body
     *
     * @return string   The HTML email body
     */
    public function htmlBody()
    {
        $this->html_body = $this->getRaw($this->html_path);

        return $this->html_body;
    }

    /**
     * Get the TEXT message body
     *
     * @return string   The TEXT email body
     */
    public function textBody()
    {
        $this->txt_body = $this->getRaw($this->txt_path);

        return $this->txt_body;
    }

    /**
     * Get the RAW message body
     *
     * @return string   The RAW email body
     */
    public function rawBody()
    {
        $this->raw_body = $this->getRaw($this->raw_path);

        return $this->raw_body;
    }

    /**
     * Get the message headers
     *
     * @return array   The headers
     */
    public function headers()
    {
        $this->headers = (array) $this->getRaw($this->apiUrl(
            'inboxes/'.$this->inbox_id.'/messages/'.$this->id.'/mail_headers'
        ))->headers;

        return $this->headers;
    }
}
