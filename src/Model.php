<?php

namespace StephaneCoinon\Mailtrap;

class Model
{
    /** @var StephaneCoinon\Mailtrap\Client */
    protected static $client;

    /**
     * Closure transforming arrays of models into another "collection-like" type.
     * If null, arrays will not be transformed.
     *
     * @var null|callable
     */
    protected static $collectionClosure = null;

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
     * Configure models to return Laravel collections instead of arrays.
     *
     * @return void
     */
    public static function returnArraysAsLaravelCollections()
    {
        static::$collectionClosure = function ($objects) {
            return collect($objects);
        };
    }

    /**
     * Configure models to return plain arrays.
     *
     * @return void
     */
    public static function returnArrays()
    {
        static::$collectionClosure = null;
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

    public function apiUrl($url)
    {
        return 'api/v1/' . $url;
    }

    /**
     * Make an API "GET" request and return the raw response.
     *
     * @param  string $uri
     * @param  array  $parameters
     * @param  array  $headers
     * @return array
     */
    public function getRaw($uri, $parameters = [], $headers = [])
    {
        return static::$client->get($uri, $parameters, $headers);
    }

    /**
     * Make an API "GET" request and return the cast response.
     *
     * @param  string $uri
     * @param  array  $parameters
     * @param  array  $headers
     * @return array|static
     */
    public function get($uri, $parameters = [], $headers = [])
    {
        $response = $this->cast($this->getRaw($uri, $parameters, $headers));

        // Reset model class for next request
        $this->model = null;

        return $response;
    }

    /**
     * Make an API "PATCH" request.
     *
     * @param  string $uri
     * @param  array  $parameters
     * @param  array  $headers
     * @return array|static
     */
    public function patch($uri, $parameters = [], $headers = [])
    {
        $response = $this->cast(static::$client->patch($uri, $parameters, $headers));
        // Reset model class for next request
        $this->model = null;

        return $response;
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
            // Cast the objects returned by the API into models
            $array = array_map(function ($object) {
                return $this->cast($object);
            }, $data);
            // Return the "collection" of models
            return $this->collect($array);
        }

        // Do we cast to our own model or a different one?
        $model = is_null($this->model) ? static::class : $this->model;

        // Cast a single object
        $instance = new $model((array) $data);

        return $instance;
    }

    /**
     * Return a "collection" of models as per type defined with
     * returnArraysAsXxx() methods.
     *
     * @param  array  $models
     * @return mixed
     */
    public function collect(array $models)
    {
        $closure = static::$collectionClosure;

        // No collection closure defined so return array of models as-is
        if (is_null($closure)) {
            return $models;
        }

        // Call the closure to transform the array into the type of collection
        // defined
        return $closure($models);
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

    /**
     * Allow to set a model attribute as if it was an instance property.
     *
     * @param  string $name attribute name
     * @param  mixed $value attribute value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}
