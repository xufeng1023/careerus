window._ = require('lodash');
window.Popper = require('popper.js').default;

window.toastr = require('toastr');
require('feather-icons').replace();

try {
    window.$ = window.jQuery = require('jquery');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    require('bootstrap');
} catch (e) {}


// window.axios = require('axios');

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    //window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    token.remove();
}
