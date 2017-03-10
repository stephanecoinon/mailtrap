<?php

namespace Tests;

use StephaneCoinon\Mailtrap\Message;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /** @test */
    public function recipientEmails()
    {
        $emails = [
            'john@example.com',
            'jane@example.org',
            'paul@example.co.uk',
        ];
        $message = new Message(['to_email' => join(', ', $emails)]);

        $this->assertEquals($emails, $message->recipientEmails());
    }
}
