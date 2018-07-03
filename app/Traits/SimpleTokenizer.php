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
        // we strip all acents
        $phraseWithAccentsStripped = $this->stripAccents($phrase);

        // and replace all punctuation with spaces
        $phraseWithoutPunctuation = strtolower(trim(preg_replace('/\W+/', ' ', $phraseWithAccentsStripped)));

        // to return the array of tokens exploded on spaces
        return array_filter(explode(' ', $phraseWithoutPunctuation));
    }

    /**
     * Strip all accents
     * @param $str
     * @return string
     */
    function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
}