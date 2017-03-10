<?php

namespace Tests;

use Mockery as m;
use StephaneCoinon\Mailtrap\Inbox;
use StephaneCoinon\Mailtrap\Message;

class InboxTest extends TestCase
{
    /** @test */
    public function fetch_inboxes_listing()
    {
        $inboxes = Inbox::all();

        $this->assertCount(1, $inboxes);
        $inbox = $inboxes[0];
        $this->assertInstanceOf(Inbox::class, $inbox);
        $this->assertEquals('Demo inbox', $inbox->name);
    }

    /** @test */
    public function fetch_valid_inbox_by_id()
    {
        $inboxId = getenv('INBOX_ID');

        $inbox = Inbox::find($inboxId);

        $this->assertEquals($inboxId, $inbox->id);
        $this->assertEquals('Demo inbox', $inbox->name);
    }

    /** @test */
    public function fetch_invalid_inbox_by_id()
    {
        $inbox = Inbox::find('dummy');

        $this->assertNull($inbox);
        $errors = $this->client->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals(404, $errors[0]->status);
        $this->assertEquals('Not Found', $errors[0]->message);
    }

    /** @test */
    public function lastMessage_returns_null_when_inbox_is_empty()
    {
        $inbox = m::mock(Inbox::class)->makePartial();
        $inbox->shouldReceive('messages')->once()->andReturn([]);

        $this->assertNull($inbox->lastMessage());
    }

    /** @test */
    public function lastMessage_returns_the_last_message_when_inbox_is_not_empty()
    {
        $messages = array_map(function ($index) {
            return new Message(['id' => $index + 1]);
        }, range(0, 4));
        // API returns messages from newest to oldest so last message is the
        // first one of the list
        $lastMessage = $messages[0];
        $inbox = m::mock(Inbox::class)->makePartial();
        $inbox->shouldReceive('messages')->once()->andReturn($messages);

        $this->assertSame($lastMessage, $inbox->lastMessage());
    }

    /** @test */
    public function hasMessageFor()
    {
        $emails = [
            'john@example.com',
            'jane@example.org',
            'paul@example.co.uk',
        ];
        $messages = array_map(function ($email) {
            return new Message(['to_email' => $email]);
        }, $emails);

        $inbox = m::mock(Inbox::class)->makePartial();
        $inbox->shouldReceive('messages')->twice()->andReturn($messages);

        $this->assertTrue($inbox->hasMessageFor('jane@example.org'));
        $this->assertFalse($inbox->hasMessageFor('stephane@example.co.uk'));
    }
}
