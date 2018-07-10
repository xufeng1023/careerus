<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="h1b工作,h1b,h1b job,工作内推,hr内推">    
    <meta name="description" content="H1B工作HR内推和工作搜索">
    
    <title>@yield('title'){{ config('app.name', 'CareerUS') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">