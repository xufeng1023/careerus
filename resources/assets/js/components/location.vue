<template>
    <div class="position-relative">
        <input type="text" name="l" class="form-control rounded-0" @input="search" :placeholder="placeholder" :value="input">
        <ul v-if="cities" class="list-group position-absolute list-group-flush box-shadow z9 w-100">
            <button type="button" class="list-group-item" v-for="city in cities" :key="city.key" @click="onClick">
                {{ city.name }},{{ city.state.initial }}
            </button>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['placeholder', 'value'],
        data() {
            return {
                input:'',
                cities: ''
            }
        },
        methods: {
            search: _.debounce(
                function (e) {
                    var s = e.target.value.trim();
                    this.input = s;
                    if(s && s.length >= 3) {
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
