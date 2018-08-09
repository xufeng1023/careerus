<!doctype html>
<html lang="zh">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @yield('style')
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/">{{ config('app.name', 'Laravel') }}</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{ auth()->user()->name }} {{ __('Logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
			@if(auth()->user()->isMaster())
              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'applies')? 'active' : '' }}" href="/admin/applies">
                  <span data-feather="flag"></span>
                  {{ __('admin.applies') }}
                </a>
              </li>
			@endif
              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'jobs')? 'active' : '' }}" href="/admin/jobs">
                  <span data-feather="list"></span>
                  {{ __('admin.job list') }}
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'catagory')? 'active' : '' }}" href="/admin/catagory">
                  <span data-feather="grid"></span>
                  {{ __('admin.catagory') }}
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'company')? 'active' : '' }}" href="/admin/company">
                  <span data-feather="home"></span>
                  {{ __('admin.company') }}
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'tags')? 'active' : '' }}" href="/admin/tags">
                  <span data-feather="tag"></span>
                  {{ __('admin.tags') }}
                </a>
              </li>
              @if(auth()->user()->isMaster())
              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'user')? 'active' : '' }}" href="/admin/user">
                  <span data-feather="users"></span>
                  {{ __('admin.users') }}
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'blog')? 'active' : '' }}" href="/admin/blog">
                  <span data-feather="radio"></span>
                  {{ __('admin.blog') }}
                </a>
              </li>

              <!-- <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'cover-letter')? 'active' : '' }}" href="/admin/cover-letter">
                  <span data-feather="mail"></span>
                  {{ __('admin.cover letter') }}
                </a>
			  </li> -->
			  
			  @if(auth()->user()->isMaster())
			  <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'green-card')? 'active' : '' }}" href="/admin/green-card">
                  <span data-feather="credit-card"></span>
                  {{ __('admin.green card') }}
                </a>
			  </li>
			  
			  <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'settings')? 'active' : '' }}" href="/admin/settings">
                  <span data-feather="settings"></span>
                  {{ __('admin.settings') }}
                </a>
              </li>
				@endif
              <!-- <li class="nav-item">
                <a class="nav-link {{ str_contains(url()->current(), 'plan')? 'active' : '' }}" href="/admin/plan">
                  <span data-feather="award"></span>
                  {{ __('admin.plans') }}
                </a>
              </li> -->
            </ul>
          </div>
        </nav>

        <main id="app" role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

            @yield('content')

        </main>
      </div>
    </div>
    <script src="{{ asset('js/admin.js') }}"></script>
    @yield('script')
  </body>
</html>