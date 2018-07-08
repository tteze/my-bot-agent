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
use Carbon\Carbon;
use Facades\App\Services\SimpleNLP;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     * @param null $lang
     */
    public function handle($lang = null)
    {
        if (!empty($lang)) {
            App::setLocale($lang);
        } else {
            App::setLocale('fr');
        }

        SimpleNLP::load(App::getLocale());

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
            $bot->reply(Lang::get('messages.name', ['surname' => Lang::get('infos.surname'), 'name' => Lang::get('infos.name')]));
        });

        $botman->hears('ask-for-age', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.age', ['age' => Carbon::parse(Lang::get('infos.birthDate'))->diffInYears(now())]));
        });

        $botman->hears('ask-for-place-living', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.living-place', ['city' => Lang::get('infos.city'), 'state' => Lang::get('infos.state')]));
        });

        $botman->hears('ask-for-reality', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.real', ['surname' => Lang::get('infos.surname')]));
        });
        $botman->hears('ask-for-help', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.help'));
        });
        $botman->hears('ask-for-state', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.state'));
        });
        $botman->hears('ask-for-place-adress', function (BotMan $bot) {
            $bot->ask(Lang::get('messages.dont-know-you'), [
                [
                    'pattern' => 'say-yes',
                    'callback' => function () use($bot) {
                        $bot->reply(Lang::get('messages.perfect'));
                    }
                ],
                [
                    'pattern' => '.*',
                    'callback' => function () use($bot) {
                        $bot->reply(Lang::get('messages.ask-for-speak-hobbies'));
                        $bot->hears('say-yes', function (BotMan $bot) {
                            $bot->startConversation(new ActivitiesConversation());
                        });
                    }
                ]
            ]);
        });

        $botman->hears('ask-for-time', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.time', ['time' => now('Europe/Paris')->format('H:i')]));
        });

        $botman->hears('ask-for-date', function (BotMan $bot) {
            $bot->reply(Lang::get('messages.date', ['date' => now('Europe/Paris')->format('d/m/Y')]));
        });
    }

    /**
     * Set the fallback that will be the answer if the bot doesn't understand
     * @param BotMan $botman
     */
    private function setFallBack(BotMan $botman) {
        $botman->fallback(function (BotMan $bot) {
            $bot->reply(Lang::get('messages.fallback'));
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
            $bot->reply(Lang::get('messages.speack-about-company'));
            $bot->startConversation(new ContactConversation());
        });

        $botman->hears('ask-for-experience', function (BotMan $bot) {
            $bot->startConversation(new ExperienceConversation());
        });

        $botman->hears('ask-for-preferences', function (BotMan $bot) {
            $bot->startConversation(new PreferencesConversation());
        });
    }
}
