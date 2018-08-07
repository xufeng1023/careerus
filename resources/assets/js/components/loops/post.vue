<template>
    <li class="list-group-item">
        <h2 class="h6 m-0">
            <a :href="post.path">{{ post.chinese_title || post.title }}</a>
        </h2>
        <div class="d-flex justify-content-between">
            <small>{{ post.company.name }} - {{ post.location }}</small>
            <small>h1b sponsor odds {{ post.sponsor_odds }}%</small>
        </div>
        <div class="text-muted h6 job-excerpt">{{ post.excerpt }}</div>
        <div class="d-flex justify-content-between">
            <div class="small text-muted">{{ post.posted_at }}</div>
            <div class="small text-danger">{{ post.availibility }}</div>
        </div>
    </li>
</template>

<script>
export default {
    props: ['post'],
    data() {
        return {
            dates:[]
        }
    }
}
</script>
