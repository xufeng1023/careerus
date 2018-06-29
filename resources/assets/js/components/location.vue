<template>
    <div class="position-relative">
        <input type="text" name="l" class="form-control rounded-0" @blur="cities=''" @input="search" :placeholder="placeholder" :value="value">
        <div v-if="cities" class="list-group position-absolute list-group-flush box-shadow z9 w-100">
            <button type="button" class="list-group-item list-group-item-action" v-for="city in cities" :key="city.key" @click="onClick">
                {{ city }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['placeholder', 'value', 'defaultLocation'],
        data() {
            return {
                cities: '',
            }
        },
        mounted() {
            this.$emit('input', this.defaultLocation);
        },
        methods: {
            search(e) {
                var s = e.target.value;
                this.$emit('input', s);
                if(s.trim()) {
                    $.ajax('/searchLocation?s='+s, {
                        dataType: 'json',
                        context: this,
                        success: data => this.cities = data
                    })
                } 
                else this.cities = '';
            },
                
            onClick(e) {
                this.$emit('input', e.target.innerText);
                this.cities = ''
            }
        }
    }
</script>
