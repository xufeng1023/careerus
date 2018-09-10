<template>
<div>
    <form class="d-inline" :class="job.is_favorited? 'filled' : ''" :action="'/job/favorite/toggle/'+job.id" method="post" @submit.prevent="toggleFavorite">
        <button type="submit" class="btn btn-sm p-0 btn-light border-0 icon">&hearts;</button>
    </form>
    <span class="favorites">{{ job.favorites_count }}</span>
</div>
</template>