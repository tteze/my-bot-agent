<?php

namespace Tests\Unit;

use Facades\App\Services\SimpleNLP;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SimpleNLPTest extends TestCase
{
    /**
     * @covers \App\Services\SimpleNLP::train()
     */
    public function testTrain() {
        /** @var Collection $result */
        $result = SimpleNLP::train(collect(['Hello World' => 'say-hello', 'Hello guys' => 'say-hello']));
        $this->assertTrue($result->isNotEmpty());
        $this->assertTrue($result->has('hello'));
        $this->assertNotEmpty($result->get('hello')->has('say-hello'));
        $this->assertEquals(2, $result->get('hello')->get('say-hello'));
    }

    /**
     * @covers \App\Services\SimpleNLP::search()
     */
    public function testSearch() {
        //test if knowledge are empty
        dd(SimpleNLP::search('Hello world'));
        $this->assertEmpty(SimpleNLP::search('Hello world'));

        SimpleNLP::train(collect(['Hello World' => 'say-hello', 'Hello guys' => 'say-hello']));
        $this->assertEquals('say-hello', SimpleNLP::search('Hello world'));

        SimpleNLP::train(collect(['How are you ?' => 'ask-for-how-you-are',]));
        $this->assertEquals('ask-for-how-you-are', SimpleNLP::search('Hello ! How are you ?'));

        SimpleNLP::train(collect(['How can you do this ?' => 'ask-for-how-do-a-thing',]));
        $this->assertEquals('ask-for-how-do-a-thing', SimpleNLP::search('How can you do everything at the same time ?'));
    }

    /**
     * @covers \App\Services\SimpleNLP::load()
     */
    public function testLoad() {
        Cache::forget('simple-nlp-knowledge-fr');

        SimpleNLP::load('fr');
        $result = SimpleNLP::search('Coucou');
        $this->assertEquals('say-hello', $result);

        Cache::forget('simple-nlp-knowledge-en');

        SimpleNLP::load('en');
        $result = SimpleNLP::search('Hello');
        $this->assertEquals('say-hello', $result);

        Cache::forget('simple-nlp-knowledge-en');
    }
}
