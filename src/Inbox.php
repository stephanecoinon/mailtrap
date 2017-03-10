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

    /**
     * Get the last/newest message.
     *
     * @return null|StephaneCoinon\Mailtrap\Message null if inbox is empty
     */
    public function lastMessage()
    {
        $messages = $this->messages();

        if (! count($messages)) {
            return null;
        }

        // API returns messages from newest to oldest so last message is the
        // first one of the list
        return $messages[0];
    }

    /**
     * Does the inbox contain a message for a given recipient e-mail?
     *
     * @param  string  $email
     * @return boolean
     */
    public function hasMessageFor($email)
    {
        $messages = $this->messages();

        foreach ($messages as $message) {
            if (in_array($email, $message->recipientEmails())) {
                return true;
            }
        }

        return false;
    }
}
