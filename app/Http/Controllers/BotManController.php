<?php

namespace App\Http\Controllers;

use App\Conversations\ActivitiesConversation;
use App\Conversations\ContactConversation;
use App\Conversations\ExperienceConversation;
use App\Conversations\PreferencesConversation;
use App\Conversations\SkillsConversation;
use App\Conversations\StudiesConversation;
use App\Http\Middleware\SimpleNLPMiddleware;
use BotMan\BotMan\BotMan;
use Facades\App\Services\SimpleNLP;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        App::setLocale('fr');
        SimpleNLP::load('fr');

        $botman = app('botman');

        $middleware = new SimpleNLPMiddleware();
        $botman->middleware->received($middleware);

        $this->simpleResponses($botman);
        $this->initConversations($botman);
        $this->setFallBack($botman);

        $botman->listen();
    }

    /**
     * Answer to simple subject wich not need to start a conversation
     * @param BotMan $botman
     */
    private function simpleResponses(BotMan $botman) {
        $botman->hears('say-hello', function (BotMan $bot) {
            $bot->reply(Lang::trans('messages.welcome'));
        });

        $botman->hears('ask-for-name', function (BotMan $bot) {
            $bot->reply("Je suis le bot qui représente Théophile Branche");
        });

        $botman->hears('ask-for-age', function (BotMan $bot) {
            $bot->reply("J'ai actuelement 22 ans et j'ai toutes mes dents");
        });

        $botman->hears('ask-for-place-living', function (BotMan $bot) {
            $bot->reply("Je vis actuelement à Lyon en France sur le quartier Garibaldi");
        });

        $botman->hears('ask-for-reality', function (BotMan $bot) {
            $bot->reply("Moi non :) Mais Théophile Branche l'est bien !");
        });
        $botman->hears('ask-for-help', function (BotMan $bot) {
            $bot->reply("Je peu répondre à toutes vos questions sur mon CV ! Et pour aller plus loin je pourrais 
            récupérer votre mail et vous recontacter si votre");
        });
        $botman->hears('ask-for-state', function (BotMan $bot) {
            $bot->reply("Je vais bien et vous");
        });
        $botman->hears('ask-for-time', function (BotMan $bot) {
            $bot->ask("On ne se connaît pas encore assez pour que je vous le dise mais si vous voulez je peux prendre votre contact ?", [
                [
                    'pattern' => 'say-yes',
                    'callback' => function () use($bot) {
                        $bot->reply('Parfait !');
                    }
                ],
                [
                    'pattern' => '.*',
                    'callback' => function () use($bot) {
                        $bot->reply("De quel sujet voudriez-vous parler ? Mes hobbies peut-être ?");
                        $bot->hears('say-yes', function (BotMan $bot) {
                            $bot->startConversation(new ActivitiesConversation());
                        });
                    }
                ]
            ]);
        });
    }

    /**
     * Set the fallback that will be the answer if the bot doesn't understand
     * @param BotMan $botman
     */
    private function setFallBack(BotMan $botman) {
        $botman->fallback(function (BotMan $bot) {
            $bot->reply("Je ne saurais pas vous répondre: Peut-être voudriez-vous parler de mes compétences ou mon expérience");
        });
    }

    private function initConversations(BotMan $botman) {
        $botman->hears('ask-for-skills', function (BotMan $bot) {
            $bot->startConversation(new SkillsConversation());
        });

        $botman->hears('ask-for-activities', function (BotMan $bot) {
            $bot->startConversation(new ActivitiesConversation());
        });

        $botman->hears('ask-for-studies', function (BotMan $bot) {
            $bot->startConversation(new StudiesConversation());
        });

        $botman->hears('ask-for-contact', function (BotMan $bot) {
            $bot->startConversation(new ContactConversation());
        });

        $botman->hears('ask-for-experience', function (BotMan $bot) {
            $bot->startConversation(new ExperienceConversation());
        });

        $botman->hears('ask-for-preferences', function (BotMan $bot) {
            $bot->startConversation(new PreferencesConversation());
        });

        $botman->hears('ask-for-place-adress', function (BotMan $bot) {
            $bot->ask("On ne se connaît pas encore assez pour que je vous le dise mais si vous voulez je peux prendre votre contact ?", [
                [
                    'pattern' => 'say-yes',
                    'callback' => function () use($bot) {
                        $bot->startConversation(new ContactConversation());
                    }
                ],
                [
                    'pattern' => '.*',
                    'callback' => function () use($bot) {
                        $bot->reply("Qu'est-ce que vous voulez savoir d'autre sur moi ?");
                    }
                ]
            ]);
        });
    }
}
