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
use Illuminate\Support\Facades\Log;

class ContactConversation extends Conversation
{
    private $company;
    private $profil;
    private $motivationsAnswer;
    private $motivations = ["le challenge", "des valeurs simples", "une valorisation du métier de développeur"];
    private $email;
    private $managementType;
    private $career;

    /**
     * @return mixed
     */
    public function run()
    {
        $this->askForCompany();
    }

    public function askForCompany() {
        $this->ask("Quel entreprise représentez-vous ?", function (Answer $answer) {
            $this->company = $answer->getText();
            $this->askForProfil();
        });
    }

    public function askForProfil() {
        $this->ask("Et quel type de profil recerchez-vous ?", function (Answer $answer) {
            $this->profil = $answer->getText();
            $this->askForMyMotivationsAnswers();
        });
    }

    public function askForMyMotivationsAnswers()
    {
        $like = implode(', ', $this->motivations);
        $this->ask('Mes principales aspirations sont: ' . $like .
            ". Qu'est-ce que votre entreprise pourrais m'apporter ?", function (Answer $answer) {
            $this->motivationsAnswer = $answer->getText();
            $this->askForManagementType();
        });
    }

    public function askForManagementType() {
        $this->ask("Quel est le type de management de votre entreprise ?", function (Answer $answer) {
            $this->managementType = $answer->getText();
            $this->askForCareer();
        });
    }

    public function askForCareer()
    {
        $this->ask("Quels sont les perspectives de carrières ?", function (Answer $answer) {
            $this->career = $answer->getText();
            $this->askForEmail();
        });
    }

    public function askForEmail() {
        $this->ask("Auriez-vous un mail que je pourrais recontacter ?", function (Answer $answer) {
            $this->email = $answer->getText();

            // todo send mail to user with his information
            Log::info('Un utilisateur a fournis son contact', compact($this->company, $this->profil, $this->motivationsAnswer, $this->email,
                $this->managementType, $this->career));

            $this->say("Super ! J'ai toutes les informations dont j'ai besoin pour vous recontacter. En attendant 
            je me ferais un plaisir de répondre à d'autres quetions si vous en avez.");
        });
    }
}