<template>
    <div class="benefits">
        <div class="benefits__item" v-for="(item, index) in options">
            <div class="fieldset">
                <div class="fieldset__header">
                    <h3>{{ item.category }}</h3>
                </div>
                <label v-for="(it, index) in item.benefits" class="field-checkbox" :for="company_id+'b'+index+it.id">
                  <span class="input-checkbox">
                    <input type="checkbox" :value="it" v-model="selection" :id="company_id+'b'+index+it.id" name="benefit">
                    <label class="input-select" :for="company_id+'b'+index+it.id"></label>
                  </span>
                  <span class="label label--checkbox label--inline">{{ it.name }}</span>
                </label>
            </div>
        </div>
    </div>

</template>
<script>
        export default {
            name: 'inputbenefits',
            data() {
                return {
                    selection: [],
                }
            },
            computed: {
            },
            watch: {
                selection: function(){
                    this.$emit('selection', this.selection)
                }
            },
            methods: {

            },
            props: {
                company_id: Number,
                options: Array,
                selected: {
                            required: false, 
                            type: Array,
                            default: []
                        }
            },
            created: function () {

            },
            mounted() {
                // Gets ids of selected timezones. Filters options comparing option_id with selected id.
                var selected_ids = [];
                this.selected.forEach(item => selected_ids.push(item.id));

                var selected_options = [];
                this.options.forEach(category => selected_options.push(category.benefits.filter(item => selected_ids.indexOf(item.id) !== -1)));

                // Merges all selected options from different categories into one
                var merged = [].concat.apply([], selected_options);

                this.selection = merged;

            }
        }
</script>
