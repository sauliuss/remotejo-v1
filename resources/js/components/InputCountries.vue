<template>
    <div class="select-regions">
        <div class="input-wrapper">
            <div class="selections" v-if="this.selection.length > 0">
                <div class="selections__item"  v-for="(item, index) in selection">
                    <div class="selection" @click="removeItem(index, item)">
                        <img v-if="item.logo" class="selection__img" :src="item.logo" alt="" title="" />
                        <span class="selection__name">{{ item.emoji }}{{ item.name }}</span>
                        <span class="btn-remove">
                            <icon class="icon-close" :name="'close--small'"></icon>
                        </span>
                    </div>
                </div>
            </div>
            <div class="input__container">
                <input ref="location_input"  v-model="autocomplete_input" placeholder="Hiring in specific countries or continents?" type="text" class="input input--wrapped" @focus="show_dropdown = true;">
                <div v-if="show_dropdown" class="dropdown">
                    <div class="dropdown__outlay" v-if="show_dropdown" @click="show_dropdown = !show_dropdown"></div>
                    <div v-if="autocomplete_input.length == 0">
                        <div role="option" class="dropdown__item" :class="{'dropdown__item--active' : item.active}" @click="addItem(item)" v-for="item in options">
                            {{item.emoji}}{{item.name}}
                        </div>
                    </div>
                    <div v-else>
                        <div role="option" :class="{is_active : item.active}" class="dropdown__item" @click="addItem(item)" v-for="(item, index) in getLocationsAutocomplete(autocomplete_input)" :key="item">
                            {{item.emoji}}{{item.name}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
        export default {
            name: 'inputcountries',
            data() {
                return {
                    locations: this.options,
                    selection: [],
                    show_dropdown: false,
                    autocomplete_input: '',
                }
            },
            props: {
                company_id: Number,
                options: {
                            required: false, 
                            type: Array,
                            default: []
                        },
                selected: {
                    type: Array,
                    required: false
                }
            },
            computed: {
            },
            watch: {
                autocomplete_input: function() {
                    if(this.autocomplete_input.length >= 1){
                        this.debouncedGetLocationsAutocomplete(this.autocomplete_input);
                    }
                    else{
                        return false;
                    }
                },
                selection: function(){
                    this.$emit('selection', this.selection)
                }
            },
            methods: {
                addItem: function(item){
                    // Creates a new array of selected items
                    var countries = [];
                    this.selection.forEach(record => {countries.push(record.name);});

                    if(countries.indexOf(item.name) == -1){
                        this.selection.push(item);
                        this.autocomplete_input = '';
                        this.$refs.location_input.focus();
                    }
                    else{
                        this.$refs.location_input.focus();
                    }
                },
                removeItem: function(index, item){
                    item.active = !item.active;
                    this.selection.splice(index, 1);
                },
                getLocationsAutocomplete: function(search_term) {

                    let result = this.options.filter(item => item.name.toLowerCase().indexOf(search_term.toLowerCase()) > -1);
                    return result;
                }

            },
            created: function () {
              this.debouncedGetLocationsAutocomplete = _.debounce(this.getLocationsAutocomplete, 500)
            },
            mounted() {
                if(typeof this.selected !== 'undefined' && this.selected.length > 0){
                    this.selection = this.selected.filter(item => item.type !== 'timezone');
                }
            },
            components: {}
        }
</script>
