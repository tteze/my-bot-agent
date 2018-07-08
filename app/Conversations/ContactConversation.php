<?php
/**
 * Created by PhpStorm.
 * User: Ttez
 * Date: 20/06/2018
 * Time: 23:50
 */

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ContactConversation extends Conversation
{
    private $company;
    private $profil;
    private $motivationsAnswer;
    private $email;
    private $managementType;
    private $career;

    public function run()
    {
        $this->askForCompany();
    }

    public function askForCompany() {
        $this->ask(Lang::get('messages.wich-company'), function (Answer $answer) {
            $this->company = $answer->getText();
            $this->askForProfil();
        });
    }

    public function askForProfil() {
        $this->ask(Lang::get('messages.wich-profil'), function (Answer $answer) {
            $this->profil = $answer->getText();
            $this->askForMyMotivationsAnswers();
        });
    }

    public function askForMyMotivationsAnswers()
    {
        $like = implode(', ', Lang::get('infos.motivations'));
        $this->ask(Lang::get('messages.interests', ['like' => $like]), function (Answer $answer) {
            $this->motivationsAnswer = $answer->getText();
            $this->askForManagementType();
        });
    }

    public function askForManagementType() {
        $this->ask(Lang::get('messages.management-type'), function (Answer $answer) {
            $this->managementType = $answer->getText();
            $this->askForCareer();
        });
    }

    public function askForCareer()
    {
        $this->ask(Lang::get('messages.career'), function (Answer $answer) {
            $this->career = $answer->getText();
            $this->askForEmail();
        });
    }

    public function askForEmail() {
        $this->ask(Lang::get('messages.mail'), function (Answer $answer) {
            $this->email = $answer->getText();

            // todo send mail to user with his information

            $this->say(Lang::get('messages.confirm-contact'));
        });
    }
}