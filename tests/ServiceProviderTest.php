<?php

namespace Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase;
use StephaneCoinon\Mailtrap\Inbox;
use StephaneCoinon\Mailtrap\Model;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function test()
    {
        $apiClient = Model::getClient();

        $this->assertInstanceOf(\StephaneCoinon\Mailtrap\Client::class, $apiClient);
        $this->assertAttributeEquals(getenv('API_TOKEN'), 'apiToken', $apiClient);
        $inboxes = Inbox::all();
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $inboxes);
        $this->assertContainsOnlyInstancesOf(Inbox::class, $inboxes);
    }

    protected function getPackageProviders($app)
    {
        return ['StephaneCoinon\Mailtrap\MailtrapServiceProvider'];
    }

    protected function getEnvironmentSetUp($app)
    {
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();

        $app['config']->set('services.mailtrap.token', getenv('API_TOKEN'));
    }
}
