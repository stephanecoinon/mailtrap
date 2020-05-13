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

    /** @test */
    public function htmlBody()
    {
        $endpoint = '/api/v1/inboxes/123456/messages/123456789/body.html';
        $this->mockClient()->shouldReceive('get')
            ->with($endpoint, [], [])->once()
            ->andReturn($html = '<div>HTML body</div>');
        $message = new Message(['html_path' => $endpoint]);

        $this->assertEquals($html, $message->htmlBody());
    }

    /** @test */
    public function textBody()
    {
        $endpoint = '/api/v1/inboxes/123456/messages/123456789/body.txt';
        $this->mockClient()->shouldReceive('get')
            ->with($endpoint, [], [])->once()
            ->andReturn($text = 'Text body');
        $message = new Message(['txt_path' => $endpoint]);

        $this->assertEquals($text, $message->textBody());
    }

    /** @test */
    public function rawBody()
    {
        $endpoint = '/api/v1/inboxes/123456/messages/123456789/body.txt';
        $this->mockClient()->shouldReceive('get')
            ->with($endpoint, [], [])->once()
            ->andReturn($raw = 'Raw body');
        $message = new Message(['raw_path' => $endpoint]);

        $this->assertEquals($raw, $message->rawBody());
    }

    /** @test */
    public function headers()
    {
        $endpoint = 'api/v1/inboxes/123456/messages/123456789/mail_headers';
        $this->mockClient()->shouldReceive('get')
            ->with($endpoint, [], [])->once()
            ->andReturn((Object) [
                'headers' => (Object) $headers = [
                    'message_id' => '5a5657f@127.0.0.1',
                    'from' =>  'Example <hello@example.com>',
                ]
            ]);
        $message = new Message([
            'inbox_id' => '123456',
            'id' => '123456789',
        ]);

        $this->assertEquals($headers, $message->headers());
    }
}
