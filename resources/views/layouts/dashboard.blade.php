@extends('layouts.app')

@section('style')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@yield('substyle')
@endsection

@section('submenu')
<div class="nav-scroller bg-white box-shadow container p-0">
    <nav class="nav nav-underline">
        <a class="nav-link {{ str_contains(url()->current(), 'applies')? 'active' : '' }}" href="/dashboard/applies">{{ __('front.applies') }}</a>
        <!-- <a class="nav-link {{ str_contains(url()->current(), 'payment')? 'active' : '' }}" href="/dashboard/payment">{{ __('front.payment') }}</a> -->
        <a class="nav-link {{ str_contains(url()->current(), 'favorites')? 'active' : '' }}" href="/dashboard/favorites">{{ __('front.favorite list') }}</a>
        <a class="nav-link {{ str_contains(url()->current(), 'account')? 'active' : '' }}" href="/dashboard/account">{{ __('front.account') }}</a>
    </nav>
</div>
@endsection

@section('content')
    @yield('subcontent')
@endsection
