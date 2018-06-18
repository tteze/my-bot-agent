<?php

namespace App\Http\Controllers;

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

        $botman->hears('J\'ai ([0-9]+) ans', function ($bot, $age) {
            $bot->reply('Tu es vraiment agé de ' . $age . ' ans ?');
        });

        $botman->fallback(function ($bot) {
            $bot->reply('Désolé j\'en sais rien moi !');
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
