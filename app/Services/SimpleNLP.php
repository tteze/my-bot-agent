<?php

namespace App\Services;

use Illuminate\Support\Collection;

class SimpleNLP
{
    private $topics;
    private $tokens;

    public function __construct()
    {
        $this->topics = collect();
        $this->tokens = collect();
    }

    /**
     * Use machine learning on examples
     * @param $example
     */
    public function train(Collection $example) {
        $example->map(function ($topic, $phrase) {
            // add topic if not exist
            if (!$this->topics->has($topic)) {
                $this->topics->put($topic, collect());
            }

            $tokens = collect($this->tokenize($phrase));
            $tokens->each(function($token) use ($topic) {
                // increment token numbers in topics Collection
                $topicInNLP = $this->topics->get($topic);
                 if (!$topicInNLP->has($token)) {
                     $topicInNLP->put($token, 1);
                 } else {
                     $topicInNLP->put($token, $topicInNLP->get($token) + 1);
                 }

                // increment topics numbers in tokens Collection
                if (!$this->tokens->has($token)) {
                     $this->tokens->put($token, collect());
                }
                $tokenInNLP = $this->tokens->get($token);
                if (!$tokenInNLP->has($topic)) {
                    $tokenInNLP->put($topic, 1);
                } else {
                    $tokenInNLP->put($topic, $tokenInNLP->get($topic) + 1);
                }
            });
        });
    }

    /**
     * Segment a text in multiple phrases
     * @param string $text
     * @return array
     */
    public function segment(string $text) {
        return array_filter(array_map('trim', preg_split('/[\!\?\.]+/', $text)));
    }

    /**
     * Tokenize a phrase
     * @param string $phrase
     * @return array
     */
    public function tokenize(string $phrase) {
        $phraseWithoutPunctuation = strtolower(trim(preg_replace('/\W+/', ' ', $phrase)));
        return array_filter(explode(' ', $phraseWithoutPunctuation));
    }

    /**
     * Search the topic corresponding to te text
     * @param $text
     * @return string
     */
    public function search($text) {
        $phrases = collect($this->segment($text));
        $tokenizedPhrases = $phrases->map([$this, 'tokenize']);

        $tokenizedPhrases->map(function ($tokenizedPhrase)  {
            $tokensWheighted = collect($tokenizedPhrase)->map(function ($token) {
                return $this->tokens->get($token);
            });
            dd($tokensWheighted);
        });

        dd($tokenizedPhrases);
        return '';
    }
}