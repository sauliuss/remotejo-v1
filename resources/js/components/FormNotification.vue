<template>
  <div class="subscribe">
<!--       <div class="subscribe__header" v-show="false">
        <div class="counter">
          <span class="counter__number">13</span>
          <div class="counter__title">Persons</div>
        </div>
        <span class="title">want to join <b>{{ company_name }}</b></span>
      </div> -->
<!--       <div class="subscribe__header" v-show="true">
        <div class="counter">
          <span class="counter__number">13</span>
          <div class="counter__title">People</div>
        </div>
        <span class="title">want to join <b>{{ company_name }}</b></span>
      </div> -->
      <div class="subscribe__body">
        <form class="form form--notify">
          <div class="toggle">
            <span class="input-toggle" @click="subscribe()">
                <input class="tgl tgl-light" id="notifications" v-model="notification" type="checkbox"/>
                <label class="tgl-btn" for="notifications" ></label>
            </span>
            <label class="label--toggle" for="notifications">Notify me of new jobs <!-- at --> <!-- <b>{{ company_name }}</b> --></label>
          </div>
          <div v-show="notification" class="fieldset fieldset--notify fieldset--full-width">
              <label v-for="category in categories" :for="'c'+category.id" class="field-checkbox field--full-width">
                <span class="input-checkbox">
                  <input  v-model="selection" :value="category" :id="'c'+category.id" type="checkbox">
                  <label :for="'c'+category.id" class="input-select"></label>
                </span>
                <span class="label label--checkbox label--inline">{{ category.name }} jobs</span>
              </label>
          </div>
        </form>    
      </div>
  </div>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
import icon from './Icon';
export default {
  name: 'formnotification',
  data () {
    return {
      notification: false,
      selection: [],
      state: {
        loaded: false,
        loading: false,
        active: false,
      }
    }
  },
  watch: {
    selection: function(){
      if(this.state.loaded){
        console.log(this.selection);
      }

      if(this.selection.length == 0){
        this.notification = false;
      }
    }
  },
  computed: {

  },
  mounted() {
    if(this.selected.length > 0){
        this.notification = true;
        this.selection = this.selected;
        this.state.loaded = true;
    }
  },
  props: {
    company_name: {
                required: true, 
                type: String,
            },
    subscribers_count: {
                required: true, 
                type: Number,
            },
    categories: {
                required: false, 
                type: Array,
            },
    selected: {
                required: false, 
                type: Array,
                default: []
            },
  },
  methods: {
    subscribe(){
      if(this.notification){
        this.selection = this.categories;
      }
    },
    submit(){
        let form_data = new FormData();
        axios
        .post('api/v1/post',form_data)
        .then(response => {
          // Enables actions on the form
          this.state.loading = false;

        })
        .catch(error => {
          // Enables actions on the form again
          this.state.loading = false;
        })
    }
  },
  components: {icon}
}
</script>
