require('./bootstrap');

window.Vue = require('vue');

$('[type=file]').change(function(e) {
    let label = $(this).siblings('label');
    let file = e.target.files[0];
    if(file) {
        label.text(file.name);
    } else {
        label.text('');
    }
});

window.helper = {
    toggle(form, val = true) {
        let btnForm = form.getAttribute('id');
        $('button[form='+btnForm+']').attr('disable', val);
    },
    disable: (buttonId) => {
        document.getElementById(buttonId).disabled = true;
    },
    enable: (buttonId) => {
        document.getElementById(buttonId).disabled = false;
    }
}
