let mix = require('laravel-mix');


mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/admin.js', 'public/js')
    .js('resources/assets/js/search.js', 'public/js')
    .js('resources/assets/js/chart.js', 'public/js')
    .js('resources/assets/js/account.js', 'public/js')
    .js('resources/assets/js/editor.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/admin.scss', 'public/css')
   .sass('resources/assets/sass/dashboard.scss', 'public/css')
   .sass('resources/assets/sass/stripe.scss', 'public/css')
   .sass('resources/assets/sass/editor.scss', 'public/css');
