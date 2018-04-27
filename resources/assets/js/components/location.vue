<template>
    <div class="position-relative">
        <input type="text" name="l" class="form-control rounded-0" @input="search" :placeholder="placeholder" :value="input">
        <div v-if="cities" class="list-group position-absolute list-group-flush box-shadow z9 w-100">
            <button type="button" class="list-group-item list-group-item-action" v-for="city in cities" :key="city.key" @click="onClick">
                {{ city }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['placeholder', 'value', 'default'],
        data() {
            return {
                input: this.default,
                cities: ''
            }
        },
        methods: {
            search: _.debounce(
                function (e) {
                    var s = e.target.value;
                    this.input = s;
                    if(s.trim()) {
                        $.ajax('/search?s='+s, {
                            dataType: 'json',
                            context: this,
                            success(data) {
                                this.cities = data;
                            }
                        })
                    } else {
                        this.cities = ''
                    }
                },
                500
            ),

            onClick(e) {
                this.input = e.target.innerText;
                this.cities = ''
            }
        }
    }
</script>
