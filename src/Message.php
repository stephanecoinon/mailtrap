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
        $partialPath = self::partialPath($this->html_path);
        $this->html_body = static::$client->get($partialPath);
        return $this->html_body;
    }

    /**
     * Get the TEXT message body
     *
     * @return string   The TEXT email body
     */
    public function textBody()
    {
        $partialPath = self::partialPath($this->txt_path);
        $this->txt_body = static::$client->get($partialPath);
        return $this->txt_body;
    }

    /**
     * Get the RAW message body
     *
     * @return string   The RAW email body
     */
    public function rawBody()
    {
        $partialPath = self::partialPath($this->raw_path);
        $this->raw_body = static::$client->get($partialPath);

        return $this->raw_body;
    }

    /**
     * Get the message header
     *
     * @return array   The headers
     */
    public function headers()
    {
        return static::$client->get('inboxes/'.$this->inbox_id.'/messages/'.$this->id.'/mail_headers');
    }


    private static function partialPath($path)
    {
        $fullPathParts = explode("/", $path);
        return join("/", array_slice($fullPathParts, 3));
    }


}
