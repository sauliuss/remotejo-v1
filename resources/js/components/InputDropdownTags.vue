<template>
    <div class="select-regions">
        <slot>
            <label :for="'industries_'+company_id" class="label">Select Industry</label>
            <label :for="'industries_'+company_id" class="label label--helper">You may choose up to 3 industries</label>
        </slot>
        <div class="input-wrapper">
            <div class="selections" v-if="this.selection.length > 0">
                <div class="selections__item"  v-for="(item, index) in selection">
                    <div class="selection" @click="removeItem(index, item)">
                        <span class="selection__name">{{ item.name }}</span>
                        <span class="btn-remove">
                            <icon class="icon-close" :name="'close--small'"></icon>
                        </span>
                    </div>
                </div>
            </div>
            <div class="input__container">
                <input :id="'industries_'+company_id"  ref="input" v-model="autocomplete_input" placeholder="Pick your company's industry" type="text" class="input input--wrapped" @focus="show_dropdown = true;">
                <div v-if="show_dropdown && checkIfMaxSelected" class="dropdown">
                    <div class="dropdown__outlay" v-if="show_dropdown" @click="show_dropdown = !show_dropdown"></div>
                    <div v-if="autocomplete_input.length == 0">
                        <div role="option" class="dropdown__item" :class="{'dropdown__item--active' : item.active}" @click="addItem(item)" v-for="item in options">
                            {{item.name}}
                        </div>
                    </div>
                    <div v-else>
                        <div role="option" :class="{is_active : item.active}" class="dropdown__item" @click="addItem(item)" v-for="(item, index) in getLocationsAutocomplete(autocomplete_input)" :key="item.id">
                            {{item.name}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'inputdropdowntags',
        data() {
            return {
                selection: [],
                show_dropdown: false,
                autocomplete_input: '',
            }
        },
        props: {
            company_id: Number,
            options: Array,
            selected: {
                type: Array,
                required: false
            }
        },
        computed: {
            checkIfMaxSelected: function(){
                if(this.selection.length < 3){
                    return true;
                }
                else{
                    return false;
                }
            }
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
                var selections = [];
                this.selection.forEach(record => {selections.push(record.id);});

                if(selections.indexOf(item.id) == -1){
                    this.selection.push(item);
                    this.autocomplete_input = '';
                    this.$refs.input.focus();
                }
                else{
                    this.$refs.input.focus();
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
          this.debouncedGetLocationsAutocomplete = _.debounce(this.getLocationsAutocomplete, 500);
        },
        mounted(){
            this.selection = this.selected;
        },
        components: {}
    }
</script>
