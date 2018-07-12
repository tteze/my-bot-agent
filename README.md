# My bot agent
Chatbot that will response about your CV and your competences. In addition it will may can tell about freelance services.

## Installation
Install the project with this both commands :

`yarn install`

`composer install`

You'll have to create your own laravel `.env` file to the source path `./`.
and run `php artisan key:generate`

And you launch your local server with `php artisan serve`

You can configure your information in `resources/lang/{lang}/infos.php`
And you can override messages in `resources/lang/{lang}/messages.php`

You can use your own pictures and replace `user.png` and `user-bubble.png` with your photographies
into the folder `public/img/`