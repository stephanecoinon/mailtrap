<?php

namespace Tests;

use Illuminate\Support\Collection;
use StephaneCoinon\Mailtrap\Model;
use Tests\TestCase;

class ModelTest extends TestCase
{
    /** @test */
    public function instance_properties_are_filled_from_an_array()
    {
        $attributes = [
            'id' => 123,
            'name' => 'Demo Inbox',
        ];

        $model = new ModelStub($attributes);

        $this->assertEquals($attributes['id'], $model->id);
        $this->assertEquals($attributes['name'], $model->name);
        $this->assertEquals($attributes, $model->getAttributes());
    }

    /** @test */
    public function it_casts_single_objects()
    {
        $plainObject = (Object) [
            'id' => 123,
            'name' => 'Demo Inbox',
        ];

        $expectedModel = new ModelStub((array) $plainObject);

        $model = (new ModelStub)->cast($plainObject);

        $this->assertInstanceOf(ModelStub::class, $model);
        $this->assertEquals($expectedModel, $model);
    }

    /** @test */
    public function it_casts_an_array_of_objects()
    {
        $plainObjects = $this->fakeApiArrayResponse();

        $expectedArray = array_map(function ($modelAttributes) {
            return new ModelStub((array) $modelAttributes);
        }, $plainObjects);

        $models = (new ModelStub)->cast($plainObjects);

        $this->assertEquals($expectedArray, $models);
    }

    /** @test */
    public function it_casts_an_array_of_objects_to_a_different_model_class()
    {
        $plainObjects = $this->fakeApiArrayResponse();

        $expectedArray = array_map(function ($modelAttributes) {
            return new AnotherModelStub((array) $modelAttributes);
        }, $plainObjects);

        $models = (new ModelStub)->model(AnotherModelStub::class)->cast($plainObjects);

        $this->assertEquals($expectedArray, $models);
    }

    /** @test */
    public function it_can_return_a_collection_instead_of_an_array()
    {
        $apiResponse = $this->fakeApiArrayResponse();

        Model::returnArraysAsLaravelCollections();

        $models = (new ModelStub)->cast($apiResponse);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $models);
        $this->assertCount(count($apiResponse), $models);
    }

    public function fakeApiArrayResponse()
    {
        return [
            (Object) ['id' => 123, 'name' => 'Demo Inbox'],
            (Object) ['id' => 456, 'name' => 'Test Inbox'],
            (Object) ['id' => 789, 'name' => 'Another Inbox'],
        ];
    }
}


class ModelStub extends Model
{

}

class AnotherModelStub extends Model
{

}
