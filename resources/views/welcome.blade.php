@include('layouts.head')
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
                overflow: hidden;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
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

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div id="app" class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
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

            <div class="content">
                <div class="m-b-md">CAREERUS</div>
                @include('_search')
                <br>
                @foreach($categories as $category)
                    <a href="/jobs{{ url()->full() === url()->current()? '?ct='.$category : '&ct='.$category }}" class="badge badge-pill badge-secondary">{{ $category }}</a>
                @endforeach
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/search.js') }}"></script>
    </body>
</html>
