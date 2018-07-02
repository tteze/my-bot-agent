<?php

namespace Tests\Unit;

use App\Traits\SimpleTokenizer;
use PHPUnit\Framework\TestCase;

class SimpleTokenizerTest extends TestCase
{
    use SimpleTokenizer;

    /**
     * @covers \App\Services\SimpleNLP::tokenize()
     */
    public function testTokenize() {
        $this->assertArraySubset(['hello'], $this->tokenize('Hello'));
        $this->assertArraySubset(['hello', 'world'], $this->tokenize('Hello World !'));
        $this->assertArraySubset(['i', 'm'], $this->tokenize('I\'m not cool'));
    }

    /**
     * @covers \App\Services\SimpleNLP::segment()
     */
    public function testSegment() {
        $this->assertArraySubset(['Hello World', 'how are you'], $this->segment('Hello World ! how are you ?'));
        $this->assertArraySubset(['Hi', 'I am not fine',], $this->segment('Hi. I am not fine...'));
    }
}
