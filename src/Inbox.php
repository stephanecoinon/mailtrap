<?php

namespace StephaneCoinon\Mailtrap;

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
        return ($model = new static)->get($model->apiUrl('inboxes'));
    }

    /**
     * Retrieve one inbox by its id.
     *
     * @param  string $id inbox id
     * @return null|static null is returned if inbox not found
     */
    public static function find($id)
    {
        return ($model = new static)->get($model->apiUrl('inboxes/'.$id));
    }

    /**
     * Get the messages in the inbox.
     *
     * @return array of StephaneCoinon\Mailtrap\Message
     */
    public function messages()
    {
        return $this->model(Message::class)->get($this->apiUrl('inboxes/'.$this->id.'/messages'));
    }

    /**
     * Get one message in the inbox by its message id.
     *
     * @param  string $id message id
     * @return null|StephaneCoinon\Mailtrap\Message null if message not found
     */
    public function message($id)
    {
        return $this->model(Message::class)->get($this->apiUrl('inboxes/'.$this->id.'/messages/'.$id));
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

    /**
     * Empty an inbox of all messages using the Mailtrap API clean method
     *
     * @param  int $id      The id of the inbox to clean; if not set, clean the inbox that is calling this
     *                      method.
     * @return Inbox        The inbox that was cleaned
     */
    public function empty($id = null)
    {
        $id = (isset($id)) ? $id : $this->attributes['id'];

        return (new static)->patch($this->apiUrl('inboxes/' . $id . '/clean'));
    }
}
