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
        <div id="app" class="d-flex justify-content-center flex-column align-items-center h-100">
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
                <h1 class="mb-3" style="text-shadow:2px 2px 2px #868686c2;">CAREERUS</h1>
                @include('_search')
            </div><br>
            <div id="welcome-page" class="container mt-5">
                <div class="row">
                    <div class="col">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="grid"></span><small>&nbsp;热门专业</small>
                        </h2><hr>
                        @foreach($categories as $category)
                            <a href="/jobs?ct={{ $category }}" class="d-block">{{ $category }}</a>
                        @endforeach
                    </div>
                    <div class="col">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="list"></span><small>&nbsp;最新工作</small>
                        </h2><hr>
                        @foreach($newJobs as $key => $job)
                            <a href="/job/{{ $job }}?i={{ $key }}" class="d-block">{{ $job->chinese_title ?: $job->title }}</a>
                        @endforeach
                    </div>
                    <div class="col">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="map-pin"></span><small>&nbsp;热门地点</small>
                        </h2><hr>
                        @foreach($hotSpots as $location)
                            <a href="/jobs?l={{ $location }}" class="d-block">{{ $location }}</a>
                        @endforeach
                    </div>
                    <div class="col">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="search"></span><small>&nbsp;热搜</small>
                        </h2><hr>
                        @foreach($hotTags as $tag)
                            <a href="/jobs?s={{ $tag }}" class="d-block">{{ $tag }}</a>
                        @endforeach
                    </div>
                    <div class="col">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="thumbs-up"></span><small>&nbsp;推荐工作</small>
                        </h2><hr>
                        @foreach($recommendedJobs as $key => $job)
                            <a href="/job/{{ $job }}?i={{ $key }}" class="d-block">{{ $job->chinese_title ?: $job->title }}</a>
                        @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/search.js') }}"></script>
    </body>
</html>
