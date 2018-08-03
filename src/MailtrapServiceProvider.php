<?php

namespace StephaneCoinon\Mailtrap;

use Illuminate\Support\ServiceProvider;
use StephaneCoinon\Mailtrap\Client;
use StephaneCoinon\Mailtrap\Model;

class MailtrapServiceProvider extends ServiceProvider
{
    /**
     * Instantiate API client and boot API models.
     *
     * @return void
     */
    public function boot()
    {
        $client = new Client(config('services.mailtrap.token'));
        Model::boot($client);
        Model::returnArraysAsLaravelCollections();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
