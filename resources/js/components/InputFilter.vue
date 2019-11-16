<template>
    <div class="field">
<!--         <div v-if="title" class="label">
            {{title}}
        </div> -->
        <div class="field__options">
            <label v-for="(item, index) in options" @click="check(index)" class="field-checkbox" :for="removeTitleSpace+index">
              <span class="input-checkbox">
                <input type="checkbox" :id="removeTitleSpace+index" :value="index" v-model="selection" :name="removeTitleSpace">
                <label class="input-select" :for="removeTitleSpace+index"></label>
              </span>
              <span class="label label--checkbox label--inline">{{item}}</span>
            </label>
        </div>
    </div>
</template>
<script>
        export default {
            name: 'inputfilter',
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
            methods: {
                check(index){
                    // if(index>0){
                    //     this.selection.splice(0)
                    // }
                }
            },
            props: {
                title: {
                    type: String,
                    required: false,
                    default: ''
                },
                options: Array
            },
            created: function () {

            },
            mounted() {
               
            }
        }
</script>
