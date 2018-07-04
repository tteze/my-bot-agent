<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class ExperienceConversation extends Conversation
{
    private $experience = [
        [
            'period' => '2015-2018',
            'job' => 'Développeur',
            'company' => 'Valeur et Capital',
            'description' => 'Développement php/mysql autour de l\'immobilier chez le client final.
                J\'ai pris la compétence sur les principaux framework php et j\'ai pu apprendre le côté fonctionnel lié à l\'immobilier.',
        ]
    ];

    /**
     * @return mixed
     */
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
        $message = 'Pendant la période ' . $this->experience[$depth]['period'] . ' '.
            'j\'ai travaille en tant que ' . $this->experience[$depth]['job'] . ' ' .
            'chez ' . $this->experience[$depth]['company'] . '.\n' .
            $this->experience[$depth]['description'];

        $this->say($message);
        if (isset($this->experience[++$depth])) {
            $this->ask('Est-ce que vous voulez savoir ce que je faisais avant ?', [
                [
                    'pattern' => 'say-yes',
                    'callback' => function () use($depth) {
                        $this->askForExperience($depth);
                    }
                ],
                [
                    'pattern' => '.*',
                    'callback' => function () {
                        $this->say('D\'accord, de quel sujet voulez-vous discuter ? Peut-être de mes compétences ?');
                    }
                ]
            ]);
        } else {
            $this->say('Parlons de vous !');
            $this->bot->startConversation(new ContactConversation());
        }
    }
}