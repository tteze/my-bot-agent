<?php

namespace App\Services;

use App\Traits\SimpleTokenizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SimpleNLP
{
    use SimpleTokenizer;

    private $topics;
    private $tokens;

    public function __construct()
    {
        $this->tokens = collect();
    }

    /**
     * Use machine learning on examples
     * @param $example
     * @return Collection
     */
    public function train(Collection $example) {
        $example->map(function ($topic, $phrase) {
            collect($this->tokenize($phrase))->each(function($token) use ($topic) {
                if (!$this->tokens->has($token)) {
                    $this->tokens->put($token, collect());
                }

                $tokenInNLP = $this->tokens->get($token);

                // increment topics numbers in tokens Collection
                if (!$tokenInNLP->has($topic)) {
                    $tokenInNLP->put($topic, 1);
                } else {
                    $tokenInNLP->put($topic, $tokenInNLP->get($topic) + 1);
                }
            });
        });
        return $this->tokens;
    }

    /**
     * Search the topic corresponding to te text
     * @param $text
     * @return string
     */
    public function search($text) {
        $phrases = collect($this->segment($text));
        $tokenizedPhrases = $phrases->map([$this, 'tokenize']);

        $mostFrequentTopics = $tokenizedPhrases->map(function ($tokenizedPhrase)  {
            return collect($tokenizedPhrase)->map(function ($token) {
                if ($this->tokens->has($token)) {
                    // get the sum of all occurrence for a topic
                    $sumTopics = $this->tokens->get($token)->sum();

                    // return all averaged occurrences foreach topics about this token
                    return $this->tokens->get($token)->map(function ($occurrenceTopic, $topic) use($sumTopics) {
                        return (object) [
                            'topic' => $topic,
                            'value' => $occurrenceTopic / $sumTopics
                        ];
                    });
                } else {
                    return collect();
                }
            })->flatten()
                // group by topics and get average for the phrase
                ->groupBy('topic')
                ->map
                ->avg('value')
                ->map(function ($occurrenceAvgTopic, $topic) {
                    return (object) [
                        'topic' => $topic,
                        'value' => $occurrenceAvgTopic
                    ];
                })
                ->sortByDesc('value')
                ->first()
                // get the most probable topic
                ->topic ?? null;
        })->mode();

        //return the last of the most present topics in the text.
        // to be more precise we would can answer to all of this topics
        $result = array_pop($mostFrequentTopics);

        if ($result === 0) {
            return 'unknown';
        }
        return $result;
    }

    public function load($lang) {
//        dd(base_path());
        if (!File::exists(base_path() . "/training/train-$lang.json")) {
            return;
        }

        $this->tokens = Cache::rememberForever("simple-nlp-knowledge-$lang", function () use ($lang) {
            $fileContent = json_decode(File::get(base_path() . "/training/train-$lang.json"));
            return $this->train(collect($fileContent));
        });
    }
}