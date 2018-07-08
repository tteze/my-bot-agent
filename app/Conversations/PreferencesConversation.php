<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Lang;

class PreferencesConversation extends Conversation
{

    public function run()
    {
        $this->askForTypeOfPreferences();
    }

    public function askForTypeOfPreferences() {
        $this->ask(Lang::get('messages.preferences'), $this->getPreferencesDetails());
    }


    public function getPreferencesDetails() {
        return collect(Lang::get('infos.preferences'))->map(function ($descriptionPreference, $preference) {
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
                $this->say(Lang::get('messages.speak-about-you'));
                $this->getBot()->startConversation(new ContactConversation());
            }
        ])->toArray();
    }
}