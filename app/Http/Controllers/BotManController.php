<?php

namespace App\Http\Controllers;

use App\Conversations\ActivitiesConversation;
use App\Conversations\ContactConversation;
use App\Conversations\ExperienceConversation;
use App\Conversations\GeneralInformationConversation;
use App\Conversations\PreferencesConversation;
use App\Conversations\SkillsConversation;
use App\Conversations\StudiesConversation;
use App\Services\SimpleNLP;
use BotMan\BotMan\BotMan;
use App\Conversations\ExampleConversation;
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
        $botman = app('botman');

        $botman->hears('.*(' . 'compétence|skill' . ').*', function (BotMan $bot) {
            $bot->startConversation(new SkillsConversation());
        });

        $botman->hears('.*(' . 'activité|passe-temps' . ').*', function (BotMan $bot) {
            $bot->startConversation(new ActivitiesConversation());
        });

        $botman->hears('.*(' . 'étude|diplôme' . ').*', function (BotMan $bot) {
            $bot->startConversation(new StudiesConversation());
        });

        $botman->hears('.*(' . 'contact|email' . ').*', function (BotMan $bot) {
            $bot->startConversation(new ContactConversation());
        });

        $botman->hears('.*(' . 'expérience|professionnel' . ').*', function (BotMan $bot) {
            $bot->startConversation(new ExperienceConversation());
        });

        $botman->hears('.*(' . 'motivations|préférences|amibitions' . ').*', function (BotMan $bot) {
            $bot->startConversation(new PreferencesConversation());
        });

        $botman->hears('.*(' . 'sur vous' . ').*', function (BotMan $bot) {
            $bot->startConversation(new GeneralInformationConversation());
        });

        $botman->hears('.*('. Lang::trans('receive.hello') . ').*', function (BotMan $bot) {
            $bot->reply(Lang::trans('messages.welcome'));
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
