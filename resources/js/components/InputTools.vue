<template>
    <div class="input-tools" @click="focusOnInput()">
        <div class="input-wrapper">
            <div class="selections" v-if="selection.length > 0">
                <div class="selections__item" v-for="(tool, index) in selection">
                    <div class="selection" @click.stop="removeTool(index)">
                        <img v-if="tool.logo" class="selection__img" :src="tool.logo" alt="" title="" />
                        <span class="selection__name">{{ tool.name }}</span>
                        <span class="btn-remove">
                            <icon class="icon-close" :name="'close--small'"></icon>
                        </span>
                    </div>
                </div>
            </div>
            <div class="input__container">
                <input class="input input--wrapped" ref="search_input" placeholder="Enter technology, software, tool name" v-model="autocomplete_input" type="text">
                <div v-if="this.autocomplete_suggestions.length > 0" class="dropdown">
                    <div class="dropdown__outlay" v-if="show_dropdown" @click="show_dropdown = !show_dropdown"></div>
                    <div role="option" class="dropdown__item" @click="addTool(tool)" v-for="tool in autocomplete_suggestions">
                            <img v-if="tool.logo" class="dropdown__item__img" :src="tool.logo" alt="" title="" />
                            <span class="tool__name">{{ tool.name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
        import Icon from './Icon.vue';
        export default {
            name: 'inputtools',
            data() {
                return {
                    selection: this.company_tools,
                    autocomplete_input: '' ,
                    autocomplete_suggestions: [],
                    show_dropdown: false,
                }
            },
            props: {
                company_tools: Array,
            },
            watch: {
                autocomplete_input: function() {
                    if(this.autocomplete_input.length >= 2){
                        this.debouncedGetToolsAutocomplete(this.autocomplete_input);
                    }
                    else{
                        this.autocomplete_suggestions = '';
                    }
                },
                selection: function(){
                    this.$emit('selection', this.selection)
                }
            },
            methods: {
                focusOnInput(event){
                    this.$refs.search_input.focus();
                },
                addTool: function(tool){
                    this.selection.push({id: tool.id, name: tool.name, logo: tool.logo});
                    this.autocomplete_input = '';
                },
                removeTool: function(index){
                    this.selection.splice(index, 1);
                },
                getToolsAutocomplete: function(search_term) {
                    this.show_dropdown = true;
                    axios({
                        method: 'get',
                        url: '/search-tools/'+search_term,
                    })
                    .then(response => {

                        // Gets ID's of already selected tools and creates array of tools that will be excluded from auto suggestion
                        var selected_ids = [];
                        this.selection.forEach(tool => {selected_ids.push(tool.id);});

                        var filtered_suggestions = response.data.filter(item => !selected_ids.includes(item.id));

                        this.autocomplete_suggestions = filtered_suggestions;

                    })
                    .catch(error => {
                        console.log(error)
                    })
                }   

            },
            created: function () {
              this.debouncedGetToolsAutocomplete = _.debounce(this.getToolsAutocomplete, 500)
            },
            mounted() {
                console.log(this.company_tools)
            }
        }
</script>
