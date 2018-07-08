<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Lang;

class StudiesConversation extends Conversation
{

    /**
     * @return mixed
     */
    public function run()
    {
        $this->askForStudy();
    }

    /**
     * Chain on study from the last to the first
     * @param int $depth
     */
    public function askForStudy($depth = 0)
    {
        $studies = Lang::get('infos.studies');
        $message = Lang::get('messages.studies', [
            'period' => $studies[$depth]['period'],
            'grade' => $studies[$depth]['grade'],
            'school' => $studies[$depth]['school'],
            'description' => $studies[$depth]['description']
        ]);

        $this->say($message);
        if (isset($studies[++$depth])) {
            $this->ask(Lang::get('messages.previous-studies'), [
                [
                    'pattern' => 'say-yes',
                    'callback' => function () use($depth) {
                        $this->askForStudy($depth);
                    }
                ],
                [
                    'pattern' => '.*',
                    'callback' => function () {
                        $this->say(Lang::get('messages.ask-for-studies-or-hobbies'));
                    }
                ]
            ]);
        } else {
            $this->say(Lang::get('messages.no-other-studies'));
            $this->bot->startConversation(new SkillsConversation());
        }
    }
}