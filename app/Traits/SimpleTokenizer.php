<?php

namespace App\Traits;


trait SimpleTokenizer
{
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
}