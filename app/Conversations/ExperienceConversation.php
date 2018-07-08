<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Lang;

class ExperienceConversation extends Conversation
{

    public function run()
    {
        $this->askForExperience();
    }

    /**
     * Chain on experience from the last to the first
     * @param int $depth
     */
    public function askForExperience($depth = 0)
    {
        $experiences = Lang::get('infos.experiences');
        $message = Lang::get('messages.experiences', [
            'period' => $experiences[$depth]['period'],
            'job' => $experiences[$depth]['job'],
            'company' => $experiences[$depth]['company'],
            'description' => $experiences[$depth]['description'],

        ]);

        $this->say($message);
        if (isset($experiences[++$depth])) {
            $this->ask(Lang::get('messages.ask-for-previous-experience'), [
                [
                    'pattern' => 'say-yes',
                    'callback' => function () use($depth) {
                        $this->askForExperience($depth);
                    }
                ],
                [
                    'pattern' => '.*',
                    'callback' => function () {
                        $this->say(Lang::get('messages.ask-for-skills'));
                    }
                ]
            ]);
        } else {
            $this->say(Lang::get('messages.ask-about-you'));
            $this->bot->startConversation(new ContactConversation());
        }
    }
}