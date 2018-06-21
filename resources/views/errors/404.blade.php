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
        <div id="app" class="d-flex justify-content-around flex-column-reverse align-items-center h-50">
            <div id="programming-languages" class="position-relative" style="opacity:0.6;"></div>
            <h1 class="mb-5" style="text-shadow:2px 2px 2px #868686c2;">CAREERUS</h1>
        </div>
        
        <div class="text-center">
            <h4>页面无法找到...</h4>
            <a href="/">回首页</a>
        </div>
        
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/animate-throw.js') }}"></script>
    </body>
</html>
