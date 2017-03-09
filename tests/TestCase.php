<?php

namespace Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;
use StephaneCoinon\Mailtrap\Client;
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
}
