//window._ = require('lodash');
window.Popper = require('popper.js').default;


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

window.toastr = require('toastr');

document.head.querySelector('meta[name="csrf-token"]').remove();
