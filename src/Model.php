<?php

namespace StephaneCoinon\Mailtrap;

class Model
{
    /** @var StephaneCoinon\Mailtrap\Client */
    protected static $client;

    /** @var array */
    protected $attributes;

    /** @var string model class to use to cast the response of the next request */
    protected $model = null;

    /**
     * Boot the model with the API client to be used to fetch the data.
     *
     * @param  StephaneCoinon\Mailtrap\Client $client
     * @return void
     */
    public static function boot($client)
    {
        static::$client = $client;
    }

    /**
     * Get the API client.
     *
     * @return StephaneCoinon\Mailtrap\Client
     */
    public static function getClient()
    {
        return static::$client;
    }

    /**
     * Make a new Model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Make an API "GET" request.
     *
     * @param  string $uri
     * @param  array  $parameters
     * @param  array  $headers
     * @return array|static
     */
    public function get($uri, $parameters = [], $headers = [])
    {
        return $this->cast(static::$client->get($uri, $parameters, $headers));
    }

    /**
     * Specify which Model to use to cast the response for the next request.
     *
     * @param  StephaneCoinon\Mailtrap\Model $model subclass of StephaneCoinon\Mailtrap\Model
     * @return static
     */
    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Cast a plain object to a Model instance or an array of plain objects to
     * an array of Model instances.
     *
     * @param  Object|array $data
     * @return null|static|array
     */
    public function cast($data)
    {
        // Cannot cast null
        if (is_null($data)) {
            return null;
        }

        // Cast an array of objects
        if (is_array($data)) {
            return array_map(function ($object) {
                return $this->cast($object);
            }, $data);
        }

        // Do we cast to our own model or a different one?
        $model = is_null($this->model) ? static::class : $this->model;

        // Cast a single object
        $instance = new $model((array) $data);

        // Reset model class for next request
        $this->model = null;

        return $instance;
    }

    /**
     * Get the model attributes as an array.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Allow to return the model attributes as if they were an instance property.
     *
     * @param  string $name attribute name
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }
}
