<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class PreferencesConversation extends Conversation
{
    private $preferences = [
        'salaire' => 'J\'estime valoir un salaire sur une fourchette de 36000€-42000€',
        'management' => 'Je préfère avoir de l\'autonomie. Je me sentirais plus à l\'aise dans une entreprise libéré que dans 
            une société paternaliste ou maternaliste.',
        'recherche' => 'Je je recherche essentielement la difficulté technique'
    ];

    public function run()
    {
        $this->askForTypeOfPreferences();
    }

    public function askForTypeOfPreferences() {
        $this->ask('De laquel de mes préférences voudriez-vous discuter ? (Ce que je recherche, le type de management 
            dans lequel je me sens le mieux, le salaire auquel je prétend.)', $this->getPreferencesDetails());
    }


    public function getPreferencesDetails() {
        return collect($this->preferences)->map(function ($descriptionPreference, $preference) {
            return [
                'pattern' => '.*(' . $preference . ').*',
                'callback' => function () use($descriptionPreference) {
                    $this->say($descriptionPreference);
                    $this->askForTypeOfPreferences();
                }
            ];
        })->push([
            'pattern' => '.*',
            'callback' => function () {
                $this->say('Parlons de votre entreprise !');
                $this->getBot()->startConversation(new ContactConversation());
            }
        ])->toArray();
    }
}