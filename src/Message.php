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
}
