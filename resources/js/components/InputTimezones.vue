<template>
    <div class="editor-timezones">
        <div class="editor-timezones__footer">
            <button @click.prevent="addAllTimezones()" class="btn btn--light btn--small">Worldwide - All Timezones</button>  
            <button @click.prevent="removeAllTimezones()" class="btn btn--light btn--small">Reset</button>  
        </div>
        <div class="editor-timezones__body" :class="type">
            <div class="editor-timezones__item" v-for="(item, index) in options">
                <div class="timezone">
                    <input type="checkbox" :value="item" :id="'utc'+index+company_id" class="timezone__checkbox" v-model="selection">
                    <label :for="'utc'+index+company_id" class="timezone__bar"></label>
                    <label :for="'utc'+index+company_id" class="timezone__label">
                        <span v-if="item.name == 0">UTC</span>
                        <span v-else-if="item.name > 0">+{{item.name}}</span>
                        <span v-else>{{item.name}}</span>
                    </label>
                </div>
            </div>       
        </div>
    </div>
</template>

<script>
        export default {
            name: 'inputtimezones',
            data() {
                return {
                    mounted: false,
                    selection: [],
                }
            },
            watch: {
                selection: function(){
                    this.$emit('selection', this.selection)
                }
            },
            props: {
                company_id: {
                    type: Number,
                    required: false
                },
                options: Array,
                selected: {
                    type: Array,
                    required: false
                },
                type: {
                    type: String,
                    required: false,
                    default: ''
                }
            },
            methods: {
                removeAllTimezones: function(){
                    this.selection = [];
                },
                addAllTimezones: function(){
                    this.selection = this.options;
                }
            },
            created: function () {
            },
            mounted() {
                // Gets ids of selected timezones. Filters options comparing option_id with selected id.
                var selected_ids = [];
                this.selected.forEach(item => {selected_ids.push(item.id);});
                this.selection = this.options.filter(item => selected_ids.indexOf(item.id) !== -1);
            },
            components: {}
        }
</script>
