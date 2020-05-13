<?php

namespace Tests;

use Dotenv\Dotenv;
use Mockery as m;
use PHPUnit\Framework\TestCase as BaseTestCase;
use StephaneCoinon\Mailtrap\Client;
use StephaneCoinon\Mailtrap\Inbox;
use StephaneCoinon\Mailtrap\Model;

class TestCase extends BaseTestCase
{
    /** @var StephaneCoinon\Mailtrap\Client */
    public $client;

    public function setUp()
    {
        $this->loadConfiguration();
        $this->bootModel();
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * Get the demo inbox.
     *
     * @return StephaneCoinon\Mailtrap\Inbox
     */
    public function getDemoInbox()
    {
        return Inbox::find(getenv('INBOX_ID'));
    }

    protected function loadConfiguration()
    {
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();

        $this->client = new Client(getenv('API_TOKEN'));
    }

    protected function bootModel()
    {
        Model::boot($this->client);
    }

    protected function mockClient()
    {
        $client = m::mock(Client::class)->makePartial();
        Model::boot($client);

        return $client;
    }
}
