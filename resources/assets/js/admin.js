require('./bootstrap');

window.Vue = require('vue');
alert(navigator.userAgent.toLowerCase());
Vue.component('applies-table', require('./components/admin/applies.vue'));

// const app = new Vue({
//     el: '#app'
// });
