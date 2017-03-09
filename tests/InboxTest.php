<?php

namespace Tests;

use StephaneCoinon\Mailtrap\Inbox;

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
}
