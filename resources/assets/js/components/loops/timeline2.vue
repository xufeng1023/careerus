<template>
    <div v-if="newDate" class="mx-3 mb-5 text-center border-top border-info" :class="{'w-100':w100}">
        <div class="d-inline-block px-3 bg-info text-white date-shadow">{{ date }}</div>
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
