<?php

namespace App\Http\Controllers;

use App\Conversations\SkillsConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
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

        $botman->hears('.*('. Lang::trans('receive.hello') . ').*', function ($bot) {
            $bot->reply(Lang::trans('messages.welcome'));
        });

        $botman->hears('.*(' . 'compétences' . ').*', function ($bot, $age) {
            $bot->startConversaction(new SkillsConversation());
        });

        $botman->fallback(function ($bot) {
            $bot->reply('Je ne sais pas');
            $bot->reply('parlons d\'autre chose');
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
