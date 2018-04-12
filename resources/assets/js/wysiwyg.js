window.Vue = require('vue');

window.Vue.component('example', require('./components/ExampleComponent.vue'));
const app = new Vue({
    el: '#app'
});