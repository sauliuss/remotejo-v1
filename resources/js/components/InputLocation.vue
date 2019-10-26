<template>
    <input type="text" name="headquaters" :id="'headquaters'+company_id" placeholder="" v-model="value" autocomplete="off" class="input">
</template>
<script>
    export default {
        name: 'inputlocation',
        data() {
            return {
                value: ''
            }
        },
        props: {
            selected: {
                type: String,
                required: false,
            },
            company_id: Number
        },
        watch: {
            value: function(){
                this.$emit('selection', this.value)
            }
        },
        methods: {
        },
        created: function () {
          
        },
        mounted() {
            var selector = 'headquaters'+this.company_id;
            var places = require('places.js');

            const options = {
                                appId: 'plLNC9CHS0EU',
                                apiKey: 'f476fdb644b25a9e5ac09f1fc8970931',
                                container: document.getElementById(selector),
                                aroundLatLngViaIP: false
                            }

            const configuration = {
                                    language: 'en',
                                    type: 'city',
                                };
            var places_autocomplete = places(options).configure(configuration);
            this.value = this.selected;
            places_autocomplete.on('change', e => this.value = e.suggestion.value);  
        },
        components: {}
    }
</script>
