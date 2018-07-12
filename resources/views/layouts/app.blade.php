@include('layouts.head')
@yield('style')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @if(str_contains(url()->current(), ['job', 'dashboard']))
                        @include('_search')
                    @endif

                    <ul class="navbar-nav ml-auto">
                        <li><a class="nav-link" href="{{ url('求职攻略') }}">{{ __('admin.blog') }}</a></li>
                        <li><a class="nav-link" href="{{ url('绿卡排期') }}">{{ __('front.visa bulletin') }}</a></li>
                        @guest
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('front.register') }}</a></li>
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('front.login') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} 
                                    @unless(auth()->user()->confirmed)
                                        <span data-feather="alert-circle" class="text-warning"></span> 
                                    @endunless
                                    <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @unless(auth()->user()->confirmed)
                                        <div class="text-warning" style="padding:0 1.5rem;max-width:120px;">{{ __('front.need to confirm email') }}</div> 
                                    @endunless
                                    <a class="dropdown-item" href="{{ url('dashboard/applies') }}">
                                        {{ __('front.dashboard') }}
                                    </a>

                                    @role('admin')
                                        <a class="dropdown-item" href="{{ url('admin/applies') }}">
                                            {{ __('admin.admin') }}
                                        </a>
                                    @endrole

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('front.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('submenu')

        <main class="py-4">@yield('content')</main>

        <!-- <footer class="footer">
            <div class="container">
                <span class="text-muted">{{ '@'.date('Y').' '.config('app.name') }} </span>
            </div>
        </footer> -->
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- <script src="https://js.stripe.com/v3/"></script>
    <script>window.stripe = Stripe('pk_test_SJpv8Y537mDjHvJY5ri7YRir')</script> -->
    @yield('script')
</body>
</html>
