@section('title')
{{ '提供H1B工作查找并且有HR内推机会 - ' }}
@endsection

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
        <div id="app" class="d-flex flex-column h-100 pt-5 mt-5">
            @if (Route::has('login'))
                <div class="d-flex fixed-top mt-2 mr-2 justify-content-end links">
                    <a href="/blog" title="求职攻略">{{ __('admin.blog') }}</a>
                    <a href="/绿卡排期" title="绿卡排期">{{ __('front.visa bulletin') }}</a>
                    @auth
                        <a id="homeDropdown" class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                            @unless(auth()->user()->confirmed)
                                <span data-feather="alert-circle" class="text-warning"></span> 
                            @endunless
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="homeDropdown">
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
                    @else
                        <a href="{{ route('register') }}" title="上传简历至HR">{{ __('front.register') }}</a>
                        <a href="{{ route('login') }}" title="登入账号">{{ __('front.login') }}</a>
                    @endauth
                </div>
            @endif

            <div class="text-center mb-3">
                <h1 class="mb-3" style="text-shadow:2px 2px 2px #868686c2;">CAREERUS</h1>
                @include('_search')
            </div>
            <div id="welcome-page" class="container mt-5">
                <div class="row">
                    <div class="col-12 col-md mb-5">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="grid"></span><small>&nbsp;热门专业</small>
                        </h2><hr>
                        @foreach($categories as $category)
                            <a href="/jobs?ct={{ $category }}" class="d-block">{{ $category }}</a>
                        @endforeach
                    </div>
                    <div class="col-12 col-md mb-5">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="list"></span><small>&nbsp;最新工作</small>
                        </h2><hr>
                        @foreach($newJobs as $key => $job)
                            <a href="{{ $job->link() }}" class="d-block">{{ $job->chinese_title ?: $job->title }}</a>
                        @endforeach
                    </div>
                    <div class="col-12 col-md mb-5">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="map-pin"></span><small>&nbsp;热门地点</small>
                        </h2><hr>
                        @foreach($hotSpots as $key => $location)
                            <a href="/jobs?l={{ $key }}" class="d-block">{{ $location }}</a>
                        @endforeach
                    </div>
                    <div class="col-12 col-md mb-5">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="search"></span><small>&nbsp;热搜</small>
                        </h2><hr>
                        @foreach($hotTags as $tag)
                            <a href="/jobs?s={{ $tag }}" class="d-block">{{ $tag }}</a>
                        @endforeach
                    </div>
                    <div class="col-12 col-md mb-5">
                        <h2 class="h4 text-muted d-flex align-items-center">
                            <span data-feather="thumbs-up"></span><small>&nbsp;推荐工作</small>
                        </h2><hr>
                        @foreach($recommendedJobs as $key => $job)
                            <a href="{{ $job->link() }}" class="d-block">{{ $job->chinese_title ?: $job->title }}</a>
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
