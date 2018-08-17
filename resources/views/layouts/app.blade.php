@include('layouts.head')
@yield('style')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light p-0 bg-white">
            <div class="container align-items-stretch">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="careerus logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse align-items-stretch" id="navbarSupportedContent">

                    <ul class="navbar-nav">
                        <li>
                            <a class="nav-link {{ url()->current() === url('/')? 'active' : '' }}" href="{{ url('/') }}" title="首页">首页</a>
                        </li>
                        <li>
                            <a class="nav-link {{ str_contains(url()->current(), 'blog')? 'active' : '' }}" href="{{ url('blog') }}" title="求职攻略">{{ __('admin.blog') }}</a>
                        </li>
                        <li>
                            <a class="nav-link {{ str_contains(url()->current(), urlencode('绿卡排期'))? 'active' : '' }}" href="{{ url('绿卡排期') }}" title="绿卡排期">{{ __('front.visa bulletin') }}</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ml-auto">
                    @guest
                            <li>
                                <a class="nav-link {{ str_contains(url()->current(), 'register')? 'active' : '' }}" href="{{ route('register') }}" title="注册">注册</a>
                            </li>
                            <li>
                                <a class="nav-link {{ str_contains(url()->current(), 'login')? 'active' : '' }}" href="{{ route('login') }}" title="登入账号">{{ __('front.login') }}</a>
                            </li>
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

        @if(url()->current() === url('/') || str_contains(url()->current(), 'job'))
            <header class="pt-5 pb-4">
                <div class="container">
                    @include('_search')
                    <nav class="nav justify-content-center mt-1">
                        <a class="nav-link text-secondary">热门搜索:</a>
                        <a title="会计" class="nav-link" href="/jobs?s=会计">会计</a>
                        <a title="销售" class="nav-link" href="/jobs?s=销售">销售</a>
                        <a title="数据" class="nav-link" href="/jobs?s=数据">数据</a>
                        <a title="工程师" class="nav-link" href="/jobs?s=工程师">工程师</a>
                        <a title="分析师" class="nav-link" href="/jobs?s=分析师">分析师</a>
                        <a title="Engineer" class="nav-link" href="/jobs?s=engineer">Engineer</a>
                    </nav>
                </div>               
            </header>
        @endif

        @yield('submenu')

        <main class="py-4">@yield('content')</main>

        <footer class="footer">
            <div class="container">
                <div class="border-top">
                    <span class="text-muted">{{ '@'.date('Y').' '.config('app.name') }} </span>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
