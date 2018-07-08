<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\Lang;

class SkillsConversation extends Conversation
{
    public function run()
    {
        $this->askForParticularSkills();
    }

    /**
     * Ask user if he want more information about a skill
     */
    public function askForParticularSkills() {
        $skills = Lang::get('infos.skills');
        $top = implode(', ', array_keys($skills['top']));
        $middle = implode(', ', array_keys($skills['middle']));
        $bottom = implode(', ', array_keys($skills['bottom']));

        $this->ask(Lang::get('messages.skills', ['top' => $top, 'middle' => $middle, 'bottom' => $bottom]), $this->getSkillsDetails());
    }

    /**
     * Prepare answers about each skills
     */
    private function getSkillsDetails() {
        return collect(Lang::get('infos.skills'))->collapse()->map(function ($experience, $skillName) {
            return [
                'pattern' => ".*($skillName).*",
                'callback' => function () use ($experience) {
                    $this->say($experience);
                    $this->askForAnotherSkill();
                }
            ];
        })->push([
            'pattern' => '.*',
            'callback' => function () {
                $this->say(Lang::get('messages.ask-for-subject'));
            }
        ])->toArray();
    }

    /**
     * Ask for another Skill
     */
    private function askForAnotherSkill() {
        $this->ask(Lang::get('messages.ask-for-other-skill'), $this->getSkillsDetails());
    }
}