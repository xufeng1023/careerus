<template>
    <div class="position-relative">
        <input type="text" name="s" class="form-control border-right-0 rounded-left" @blur="jobs=''" @input="search" :placeholder="placeholder" :value="value">
        <div v-if="jobs" class="list-group position-absolute list-group-flush box-shadow z9 w-100">
            <a :href="job | jobLink" class="list-group-item list-group-item-action" v-for="job in jobs" :key="job.key" @click="onClick">
                {{ job.chinese_title || job.title }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['placeholder', 'value', 'defaultJob'],
        data() {
            return {
                jobs: ''
            }
        },
        filters:{
            jobLink(job) {
                return '/job/' +  job.title + '?i=' + job.identity;
            }
        },
        mounted() {
            this.$emit('input', this.defaultJob);
        },
        methods: {
            search(e) {
                var s = e.target.value;
                this.$emit('input', s);
                if(s.trim()) {
                    $.ajax('/searchJob?s='+s, {
                        dataType: 'json',
                        context: this,
                        success: data => this.jobs = data
                    })
                } 
                else this.jobs = '';
            },

            onClick(e) {
                this.$emit('input', e.target.innerText);
                this.jobs = ''
            }
        }
    }
</script>

<style>
#searchForm .form-control {
    border-radius: 0;
}
</style>