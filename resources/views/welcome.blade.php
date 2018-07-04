<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .image-user {
            height: 100%;
            max-width: 50%;
            display: inline-block;
            background-color: lightblue;
        }
        .content {
            width: 30%;
            display: inline-block;
            text-align: center;
        }
        .image-arrow {
            position: absolute;
            bottom: 30%;
            right: 30%;
        }
    </style>
    <title>My agent bot</title>
</head>
<body>
    <img src="{{ asset('/img/user.jpg') }}" class="image-user"/>

    <div class="content">
        <h1>Théophile Branche</h1>
        <h2>Développeur full-stack</h2>
        <p>
            Vous pouvez discuter avec mon Agent bot si vous voulez des informations sur mon CV !
            Et si vos voulez en savoir plus sur comment il a été fait il vous suffit de d'aller voir sur
            <a href="https://github.com/tteze/my-bot-agent">GITHUB</a>
        </p>
        <img src="{{ asset('/img/arrow-to-bot') }}" class="image-arrow">
    </div>
<script src="{{ asset('/js/app.js') }}"></script>
@include('botman')
</body>
</html>