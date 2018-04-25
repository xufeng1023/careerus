<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="noindex">
    
    <title>@yield('title'){{ config('app.name', 'CareerUsa') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">