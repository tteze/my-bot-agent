<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">
    <title>@lang('infos.surname') @lang('infos.name')</title>
</head>

<body class="w-100">
    <div class="lang d-flex justify-content-end mb-5">
        @if(app()->getLocale() === 'fr')
            <a href="{{ url('/en') }}"><img src="{{ asset('/img/lang-english.jpg') }}" /></a>
        @else
            <a href="{{ url('/') }}"><img src="{{ asset('/img/lang-french.jpg') }}" /></a>
        @endif
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-10 align-self-center content text-center">
            <h1 class="title">@lang('infos.surname') @lang('infos.name')</h1>
            <h2 class="subtitle">@lang('infos.job')</h2>
            <p>@lang('infos.accroche')</p>
            <img src="{{ asset('/img/arrow.png') }}" class="image-arrow">
        </div>
    </div>
</body>

<script src="{{ asset('/js/app.js') }}"></script>
@include('botman')
