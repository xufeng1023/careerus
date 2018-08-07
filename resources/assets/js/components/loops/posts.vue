<template>
    <ul class="list-group list-group-flush box-shadow mb-3">
        <template v-for="post in posts">
            <timeline v-show="!dates.includes(post.created_at)" :date="post.created_at" :key="post.id"></timeline>
            <post :post="post" :key="post.identity"></post>
        </template>
        <slot></slot>
    </ul>
</template>

<script>
import post from './post.vue';
import timeline from './timeline.vue';

export default {
    props: ['posts'],
    components: {post,timeline},
    data() {
        return {
            dates:[]
        }
    },
    created() {
        window.Event.$on('new date', date => {
           // if(!this.dates.includes(date)) this.dates.push(date);
        });
    }
}
</script>
