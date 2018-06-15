@include('layouts.head')
        <style>
            html, body {
                height: 100vh;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div id="app" class="d-flex justify-content-center align-items-center h-100">
            @if (Route::has('login'))
                <div class="d-flex fixed-top mt-2 mr-2 justify-content-end links">
                    <a href="/blog">{{ __('admin.blog') }}</a>
                    @auth
                        <a id="homeDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="homeDropdown">
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
                    @else
                        <a href="{{ route('register') }}">{{ __('front.register') }}</a>
                        <a href="{{ route('login') }}">{{ __('front.login') }}</a>
                    @endauth
                </div>
            @endif

            <div class="text-center">
                <div id="programming-languages" class="position-relative" style="opacity:0.6;"></div>
                <h1 class="mb-5" style="text-shadow:2px 2px 2px #868686c2;">CAREERUS</h1>
                @include('_search')
                <div class="w-75 mx-auto mt-3">
                    @foreach($categories as $category)
                        <a href="/jobs{{ url()->full() === url()->current()? '?ct='.$category : '&ct='.$category }}" class="badge badge-pill badge-secondary">{{ $category }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/search.js') }}"></script>
        <script src="{{ asset('js/animate-throw.js') }}"></script>
    </body>
</html>
