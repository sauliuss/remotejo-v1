<template>
  <form class="form--alert" action="post">
      <label class="label label--light">Be the first to know about new remote jobs at&nbsp;<b>{{company_name}}</b></label>
      <div class="input-wrapper input-wrapper--alert">
        <input id="email-alert" class="input input--wrapped input--alert" type="email" placeholder="Enter your email address">
        <button type="submit" @click.prevent="submit(company_name)" class="btn btn--yellow btn--medium">
          <svg class="icon icon--fill icon--left icon-email" aria-hidden="true">
              <use xlink:href="/img/svg_symbols.svg#icon-email"></use>
          </svg>
          <span class="btn__text">Create Job Alert</span>
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
      }
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
