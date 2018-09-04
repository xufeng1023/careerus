<template>
    <div v-if="newDate" class="d-flex align-items-center timeline-date my-1" :class="{'w-100':w100}">
        <div class="m-0 h5 date">{{ date }}</div>
    </div>
</template>

<script>
export default {
    props: ['date'],
    data() {
        return {
            w100: false,
            newDate: false,
        }
    },
    mounted() {
        if(window.dates.indexOf(this.date) === -1) {
            window.dates.push(this.date);
            this.w100 = true;
            this.newDate = true;
        }
    }
}
</script>