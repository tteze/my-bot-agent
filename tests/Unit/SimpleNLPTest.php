<?php

namespace Tests\Unit;

use Facades\App\Services\SimpleNLP;
use Tests\TestCase;

class SimpleNLPTest extends TestCase
{
    /**
     * @covers \App\Services\SimpleNLP::train()
     */
    public function testTrain() {
        // todo
        SimpleNLP::search('Hello World ! how are you ?');
        $this->assertTrue(true);
    }

    /**
     * @covers \App\Services\SimpleNLP::tokenize()
     */
    public function testTokenize() {
        $this->assertArraySubset(['hello'], SimpleNLP::tokenize('Hello'));
        $this->assertArraySubset(['hello', 'world'], SimpleNLP::tokenize('Hello World !'));
        $this->assertArraySubset(['i', 'm'], SimpleNLP::tokenize('I\'m not cool'));
    }

    /**
     * @covers \App\Services\SimpleNLP::segment()
     */
    public function testSegment() {
        $this->assertArraySubset(['Hello World', 'how are you'], SimpleNLP::segment('Hello World ! how are you ?'));
        $this->assertArraySubset(['Hi', 'I am not fine',], SimpleNLP::segment('Hi. I am not fine...'));
    }

    /**
     * @covers \App\Services\SimpleNLP::search()
     */
    public function testSearch() {
        // todo
        $this->assertTrue(true);
    }
}
