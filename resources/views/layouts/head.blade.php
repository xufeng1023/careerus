<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="h1b工作,h1b,h1b job,工作内推,hr内推">    
    <meta name="description" content="每日都有最新的H1B工作介绍,尤其是在纽约和加州这种华人公司较多的大城市,并且可直接向HR递交简历申请并有机会得到内推,同时还有不断更新的原创美国求职攻略可参考.">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <title>@yield('title'){{ config('app.name', 'CareerUS') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110866523-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-110866523-2');
    </script>