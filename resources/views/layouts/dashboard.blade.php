@extends('layouts.app')

@section('style')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('submenu')
<div class="nav-scroller bg-white box-shadow container p-0">
    <nav class="nav nav-underline">
        <a class="nav-link active" href="/dashboard/applies">{{ __('front.applies') }}</a>
        <a class="nav-link" href="/dashboard/account">{{ __('front.account') }}</a>
    </nav>
</div>
@endsection

@section('content')
    @yield('subcontent')
@endsection
