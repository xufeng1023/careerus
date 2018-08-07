<template>
    <li v-if="this.$parent.dates.includes(date)">{{ date }}</li>
</template>

<script>
export default {
    props: ['date'],
    created() {
        window.Event.$emit('new date', this.date);
    }
}
</script>
