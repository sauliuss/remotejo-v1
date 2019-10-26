<template>
    <div class="field">
        <div class="label">
            {{title}}
        </div>
        <div class="field__options">
            <label v-for="(item, index) in options" class="field-checkbox" :for="company_id+removeTitleSpace+index">
              <span class="input-radio">
                <input type="radio" :id="company_id+removeTitleSpace+index" :value="index" v-model="selection" :name="removeTitleSpace">
                <label class="input-select" :for="company_id+removeTitleSpace+index"></label>
              </span>
              <span class="label label--checkbox label--inline">{{item}}</span>
            </label>
        </div>
    </div>
</template>
<script>
        export default {
            name: 'inputcompanytype',
            data() {
                return {
                    selection: []
                }
            },
            computed: {
                removeTitleSpace: function(){
                    return this.title.replace(/\s+/g, '');
                }
            },
            watch: {
                selection: function(){
                    this.$emit('selection', this.selection)
                }
            },
            props: {
                company_id: Number,
                title: String,
                options: Array,
                selected: {
                            required: false, 
                            type: Number,
                            default: []
                        }
            },
            created: function () {

            },
            mounted() {
                this.selection = this.selected;
            }
        }
</script>
