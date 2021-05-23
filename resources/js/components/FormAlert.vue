<template>
  <form class="form--alert" action="post">
      <label class="label label--light">Be the first to know about new remote jobs at&nbsp;<b>{{company_name}}</b></label>
      <div class="fieldset fieldset--notify fieldset--full-width">
          <label v-for="category in job_categories" :for="'c'+category.id" class="field-checkbox field--full-width">
            <span class="input-checkbox">
              <input  v-model="selection" :value="category" :id="'c'+category.id" type="checkbox">
              <label :for="'c'+category.id" class="input-select"></label>
            </span>
            <span class="label label--checkbox label--inline">{{ category.name }} jobs</span>
          </label>
      </div>
      <div class="input-wrapper input-wrapper--alert">
        <input id="email-alert" class="input input--wrapped input--alert" type="email" placeholder="Enter your email address">
        <button type="submit" @click.prevent="submit(company_name)" class="btn btn--yellow btn--medium btn--job-alert">
          <svg class="icon icon--fill icon--left icon-email" aria-hidden="true">
              <use xlink:href="/img/svg_symbols.svg#icon-email"></use>
          </svg>
          <span class="btn__text">Set job alert</span>
        </button>
      </div>
      <label v-if="this.state.error" for="email-alert" class="alert alert--error">Ooops</label>
  </form>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
export default {
  name: 'formalert',
  data () {
    return {
      state: {
        loaded: false,
        loading: false,
        active: false,
        error: false,
      },
      selection: [],
    }
  },
  watch: {
  },
  computed: {

  },
  mounted() {

  },
  props: {
    company_name: {
      required: true, 
    },
    job_categories: {
      required: true
    }
  },
  methods: {
    submit(){
        let form_data = new FormData();
        axios
        .post('api/v1/alert',form_data)
        .then(response => {
          // Enables actions on the form
          this.state.loading = false;

        })
        .catch(error => {
          // Enables actions on the form again
          this.state.loading = false;
        })
    }
  }
}
</script>
