<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FeaturesCommonDictionariesTest extends TestCase
{
    use InteractsWithDatabase;
    use DatabaseMigrations;

    /** @test */
    public function dictionary_can_be_created()
    {
        $user = factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $baseAttributes = factory(\App\Dictionary::class)->make()->getAttributes();
        $data = ['dictionary' => [array_merge($baseAttributes, ['items' => [], 'id' => null])]];


        $this->json('post', '/dictionaries', $data)
            ->assertResponseOk()
            ->seeJson(['data' => ['redirect' => 'http://localhost/dictionaries']])
            ->seeInDatabase('dictionaries', $baseAttributes);
    }

    /** @test */
    public function dictionary_can_be_stored_with_items()
    {
        $user = factory(\App\Models\User::class)->create();
        $this->actingAs($user);

        $baseAttributes = factory(\App\Dictionary::class)->make()->getAttributes();
        $baseItems = factory(\App\DictionaryValue::class)->times(5)->make()->map(function($item){
            return $item->getAttributes();
        });

        $items = $baseItems->map(function ($item){
            return array_merge($item, ['id'=> null]);
        });

        $data = ['dictionary' => [array_merge($baseAttributes, ['items' => $items->all(), 'id' => null])]];


        $this->json('post', '/dictionaries', $data)
            ->assertResponseOk()
            ->seeJson(['data' => ['redirect' => 'http://localhost/dictionaries']])
            ->seeInDatabase('dictionaries', $baseAttributes);

        $baseItems->each(function($record){
            $this->seeInDatabase('dictionary_values', $record);
        });

    }
}