<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class ActivitiesConversation extends Conversation
{
    private $activities = [
        'top' => [
            'musculation' => 'Je vais à la salle en moyenne tous les trois jours.',
            'course à pied' => 'Je cours en moyenne une fois par semaine sauf quant je me lance un grand défi. Par exemple je me suis entraîner
            pour le marathon du RUN IN LYON 4 à 5 fois par semaine durant 8 semaines.',
            'guitare' => 'J\'ai appris la guitare il y a deux ans mais mon ouverture aux différents styles musicaux m\' a permis de progresser 
            vite sur différents points.'
        ],
        'middle' => [
            'défis sportifs' => 'Cette été j\'entreprends la montée du mont blanc.',
            'voyages' => 'L\'an dernier je suis partis en Islande et je prévois un tour du monde d\'ici les 4 prochaines années'
        ],
        'bottom' => [
            'brassage de bière' => 'Depuis quelque mois je brasse moi-même ma bière ce qui est chronophage sur une soirée mais apporte un aggréable
            retour sur investissement ^^'
        ]
    ];

    /**
     * @return void
     */
    public function run()
    {
        $this->askForParticularActivities();
    }

    /**
     * Ask user if he want more information about a skill
     */
    public function askForParticularActivities() {
        $top = implode(', ', array_keys($this->activities['top']));
        $middle = implode(', ', array_keys($this->activities['middle']));
        $bottom = implode(', ', array_keys($this->activities['bottom']));

        $this->ask('Je passe la plupart de mon temps sur des passes-temps telles que ' . $top . '. Mais je m\'occupe aussi en faisant un peu de ' .
            $middle . '. et je fais parfois un peu de ' . $bottom . '. Vous-êtes intéressez pour en savoir plus sur l\'une de 
            mes activités ?', $this->getActivitiesDetails());
    }

    /**
     * Prepare answers about each skills
     */
    private function getActivitiesDetails() {
        return collect($this->activities)->flatten()->map(function ($activity, $activityName) {
            return [
                'pattern' => '.*(' . $activityName . ').*',
                'callback' => function () use ($activity) {
                    $this->say($activity);
                    $this->askForAnotherActivity();
                }
            ];
        })->push([
            'pattern' => '.*',
            'callback' => function () {
                $this->say('Qu\'est ce qui vous intéresserais alors ?
                Peut-être mon cursus universitaire ou mes expérience en entreprise ?');
            }
        ])->toArray();
    }

    /**
     *
     */
    private function askForAnotherActivity() {
        $responses = $this->getActivitiesDetails();
        $this->ask('Vous voulez en savoir plus sur une autre de mes activités ?', $responses);
    }
}