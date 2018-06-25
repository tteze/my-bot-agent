<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class StudiesConversation extends Conversation
{
    private $studies = [
        [
            'period' => '2015-2018',
            'grade' => 'Master Technologies de l\'information et du web',
            'school' => 'Université Lyon 1 Claude Bernard',
            'description' => 'J\'ai fais un master car je voulais avoir plus d\'autonomie. C\'est l\'une des compétences que j\'ai 
            développé en plus des modules théoriques qui me permettent aujourd\'hui d\'appréhender correctemnt les problèmes qui s\'offre à moi
            quelque soit les outils, frameworks ou langages avec lesquels je travail.',
        ],
        [
            'period' => '2013-2015',
            'grade' => 'DUT en informatique générale',
            'school' => 'Université Lyon 1 Claude Bernard département de Bourg en Bresse',
            'description' => 'L\'IUT est une formation qui m\'a beaucoup professionalisé et qui m\'a permis aussi de faire de la 
            pratique très tôt.',
        ]
    ];

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
        $message = 'Pendant la période ' . $this->studies[$depth]['period'] . ' '.
            'j\'ai étudié pour avoir le diplôme ' . $this->studies[$depth]['grade'] . ' ' .
            'dans l\'école ' . $this->studies[$depth]['school'] . '.\n' .
            $this->studies[$depth]['description'];

        $this->say('');
        $this->ask('Est-ce que vous voulez connaître mes diplômes précédents ?', [
            [
                'pattern' => '.*(oui|yep|allez).*',
                'callback' => function () use($depth) {
                    if (isset($this->experience[$depth++])) {
                        $this->askForStudy($depth);
                    } else {
                        $this->say('Je n\'ai pas fais plus d\'étude mais par contre nous pouvons parler de mon expérience 
                         professionnel!');
                        $this->bot->startConversation(new ExperienceConversation());
                    }
                }
            ],
            [
                'pattern' => '.*',
                'callback' => function () {
                    $this->say('Je vous laisse choisir ce de quoi vous voulez parler ? 
                    Peut-être mes compétences ou mes passes-temps ?');
                }
            ]
        ]);
    }
}