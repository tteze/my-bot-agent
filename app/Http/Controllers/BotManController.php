<?php

namespace App\Http\Controllers;

use App\Conversations\ActivitiesConversation;
use App\Conversations\ContactConversation;
use App\Conversations\ExperienceConversation;
use App\Conversations\GeneralInformationConversation;
use App\Conversations\PreferencesConversation;
use App\Conversations\SkillsConversation;
use App\Conversations\StudiesConversation;
use App\Http\Middleware\SimpleNLPMiddleware;
use BotMan\BotMan\BotMan;
use App\Conversations\ExampleConversation;
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

        $botman->hears('say-hello', function (BotMan $bot) {
            $bot->reply(Lang::trans('messages.welcome'));
        });

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

        $botman->hears('ask-for-personal-information', function (BotMan $bot) {
            $bot->startConversation(new GeneralInformationConversation());
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Je ne peux pas vous répondre :/ Peut-être voudriez-vous parler de mes compétences ou mon expérience');
        });


        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }


}
