Vue.component('post-list', require('./components/loops/posts.vue'));

window.Event = new Vue;

new Vue({
    el: '#postsPage',
});