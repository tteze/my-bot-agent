<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Lang;

class ActivitiesConversation extends Conversation
{

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
        $activities = Lang::get('infos.activities');
        $top = implode(', ', array_keys($activities['top']));
        $middle = implode(', ', array_keys($activities['middle']));
        $bottom = implode(', ', array_keys($activities['bottom']));

        $this->ask(Lang::get('messages.activities', [
            'top' => $top,
            'middle' => $middle,
            'bottom' => $bottom,
        ]), $this->getActivitiesDetails());
    }

    /**
     * Prepare answers about each skills
     */
    private function getActivitiesDetails() {
        return collect(Lang::get('infos.activities'))->collapse()->map(function ($activity, $activityName) {
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
                $this->say(Lang::get('messages.then-ask-for-cursus'));
            }
        ])->toArray();
    }

    /**
     *
     */
    private function askForAnotherActivity() {
        $this->ask(Lang::get('messages.other-activities'), $this->getActivitiesDetails());
    }
}