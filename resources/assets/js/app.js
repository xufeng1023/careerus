require('./bootstrap');

window.Vue = require('vue');

Vue.component('location', require('./components/location.vue'));

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

// const app = new Vue({
//     el: '#app'
// });
