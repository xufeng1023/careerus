<template>
    <div v-if="newDate" class="mx-3 mb-5 d-flex justify-content-center" :class="{'w-100':w100}">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <div class="d-flex">
                <div class="shelf-left"></div>
                <div class="shelf-right"></div>
            </div>
            <div class="px-3 bg-info text-white date-shadow shelf-date rotate">{{ date }}</div>
        </div>
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
<style>
.shelf-left {
    height: 30px;
    width: 2px;
    background: #17a2b8;
    transform: skewX(-27deg) translateX(-22px);
}
.shelf-right {
    height: 25px;
    width: 3px;
    background: #17a2b8;
    transform: skewX(57deg) translate(2px, 0px);
}
.shelf-date {
    transform: skewY(-5deg) translateY(-3px)
}
.rotate:hover, .rotate:focus {
    cursor: pointer;
    animation-name: swing;
    animation-duration: 500ms;
    animation-iteration-count: 1;
    animation-timing-function: linear;
}

@keyframes swing {
    0%   {transform: rotate(-10deg);}
    5% {transform: rotate(5deg);}
    10% {transform: rotate(initial);}
    15% {transform: rotate(-10deg);}
    20% {transform: rotate(initial);}
    25% {transform: rotate(5deg);}
    30% {transform: rotate(initial);}
    35% {transform: rotate(-10deg);}
    40% {transform: rotate(initial);}
    45% {transform: rotate(5deg);}
    100% {transform: rotate(initial);}
}
</style>