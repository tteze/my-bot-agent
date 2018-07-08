<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html {
            height: 100%;
            padding: 0;
            font-family: sans-serif;
        }
        body {
            margin: 0;
            background: url("{{ asset('/img/user.jpg') }}") no-repeat;
            background-size: 30%;
            background-position-y: -250px;
        }
        .content {
            width: 30%;
            text-align: center;
            float: left;
            margin-left: 35%;
            margin-top: 10%;

        }
        .image-arrow {
            position: fixed;
            bottom: 100px;
            right: 180px;
            width: 150px;
            transform: rotate(20deg);
        }
        .lang {
            float: right;
            margin: 10px 10px 0 0;
        }
        .lang img {
            width: 70px;
        }
    </style>
    <title>@lang('infos.surname') @lang('infos.name')</title>
</head>
<body>
    <div class="content">
        <h1>@lang('infos.surname') @lang('infos.name')</h1>
        <h2>@lang('infos.job')</h2>
        <p>
            @lang('infos.accroche')
        </p>
        <img src="{{ asset('/img/arrow.png') }}" class="image-arrow">
    </div>

    <div class="lang">
        @if(app()->getLocale() === 'fr')
            <a href="{{ url('/en') }}"><img src="{{ asset('/img/lang-english.jpg') }}" /></a>
        @else
            <a href="{{ url('/') }}"><img src="{{ asset('/img/lang-french.jpg') }}" /></a>
        @endif
    </div>
<script src="{{ asset('/js/app.js') }}"></script>
@include('botman')
</body>
</html>