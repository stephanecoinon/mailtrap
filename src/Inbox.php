<?php

namespace StephaneCoinon\Mailtrap;

use StephaneCoinon\Mailtrap\Exceptions\MailtrapException;
use StephaneCoinon\Mailtrap\Message;

class Inbox extends Model
{
    /**
     * Return the listing of all inboxes.
     *
     * @return array of StephaneCoinon\Mailtrap\Inbox
     */
    public static function all()
    {
        return (new static)->get('inboxes');
    }

    /**
     * Retrieve one inbox by its id.
     *
     * @param  string $id inbox id
     * @return null|static null is returned if inbox not found
     */
    public static function find($id)
    {
        return (new static)->get('inboxes/'.$id);
    }

    /**
     * Get the messages in the inbox.
     *
     * @return array of StephaneCoinon\Mailtrap\Message
     */
    public function messages()
    {
        return $this->model(Message::class)->get('inboxes/'.$this->id.'/messages');
    }

    /**
     * Get one message in the inbox by its message id.
     *
     * @param  string $id message id
     * @return null|StephaneCoinon\Mailtrap\Message null if message not found
     */
    public function message($id)
    {
        return $this->model(Message::class)->get('inboxes/'.$this->id.'/messages/'.$id);
    }
}
