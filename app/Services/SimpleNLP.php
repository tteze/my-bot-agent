<?php

namespace App\Services;

use App\Traits\SimpleTokenizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class SimpleNLP
{
    use SimpleTokenizer;

    const TRUST = 0.5;

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
        $example->each(function ($topic, $phrase) {
            collect($this->tokenize($phrase))->each(function($token) use ($topic) {
                if (!$this->tokens->has($token)) {
                    $this->tokens->put($token, (object) [
                        'nbOccurences' => 0,
                        'topics' => collect()
                    ]);
                }
                $tokenInNLP = $this->tokens->get($token);

                // increment topics numbers in tokens Collection
                if (!$tokenInNLP->topics->has($topic)) {
                    $tokenInNLP->topics->put($topic, 1);
                } else {
                    $tokenInNLP->topics->put($topic, $tokenInNLP->topics->get($topic) + 1);
                }
                $tokenInNLP->nbOccurences += 1;
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
                    $nbOccurences = $this->tokens->get($token)->nbOccurences;

                    // return all averaged occurrences foreach topics about this token
                    return $this->tokens->get($token)->topics->map(function ($occurrenceTopic, $topic) use($nbOccurences) {
                        return (object) [
                            'topic' => $topic,
                            'value' => $occurrenceTopic / $nbOccurences
                        ];
                    });
                } else {
                    return collect();
                }
            })->flatten()
                // group by topics and get average for the phrase
                ->groupBy('topic')
                ->map->max('value')
                ->map(function ($occurrenceAvgTopic, $topic) {
                    return (object) [
                        'topic' => $topic,
                        'value' => $occurrenceAvgTopic
                    ];
                })
                ->where('value', '>', static::TRUST)
                // get the most probable topic
                ->sortByDesc('value')
                ->first()
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
        if (!File::exists(base_path() . "/training/train-$lang.json")) {
            return;
        }

        $this->tokens = Cache::rememberForever("simple-nlp-knowledge-$lang", function () use ($lang) {
            $fileContent = json_decode(File::get(base_path() . "/training/train-$lang.json"));
            return $this->train(collect($fileContent));
        });
    }
}