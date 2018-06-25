<?php
/**
 * Created by PhpStorm.
 * User: Ttez
 * Date: 20/06/2018
 * Time: 22:33
 */

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;

class GeneralInformationConversation extends Conversation
{
    private $city = 'Lyon';
    private $birthDate = '1995-11-01';
    private $permis = true;

    public function run()
    {
        $this->askForPersonalInformation();
    }

    public function askForPersonalInformation() {
        $this->ask('Peut-être que vous voudriez en savoir plus sur moi ? et mes disponibilités ?', [
            [
                'pattern' => 'oui|yep',
                'callback' => function () {
                    $age = now()->diffInYears(Carbon::parse($this->birthDate));
                    $this->say('J\'habite à ' . $this->city . '. J\'ai ' . $age . ' ans. ' .
                        ($this->permis ? 'J\'ai' : 'Je n\'ai pas') . ' le permis');
                }
            ],
            [
                'pattern' => '.*',
                'callback' => function () {
                    $this->askForPreferences();
                }
            ]
        ]);
    }

    public function askForPreferences() {
        $this->ask('Vous voulez en savoir plus sur mes préférences ?', [
            [
                'pattern' => 'oui|yep',
                'callback' => function () {
                    $this->getBot()->startConversation(new PreferencesConversation());
                }
            ],
            [
                'pattern' => '.*',
                'callback' => function () {
                    $this->say('Je vous laisse choisir le sujet alors :)');
                }
            ]
        ]);
    }
}