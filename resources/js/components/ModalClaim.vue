<template>
  <div>
    <button @click="showModal()" class="btn btn--small btn--claim">
        <span class="btn__text">
            Claim Profile
        </span>
    </button>
    <div class="modal-outlay" @click.self="closeModal()" v-show="state.active">
        <div class="modal">
          <div class="modal__header modal__header--claim">
            <span class="modal__title">Claim <b>{{ company_name }}</b> profile</span>
            <a href="#" class="btn-close" @click.prevent.stop="closeModal()">
              <icon :name="'close--normal'"></icon>
            </a>
          </div>
          <div class="modal__body modal__body--claim">

            <div class="avatar avatar--modal">
                  <img class="avatar__img is-company avatar__img--small avatar__img--modal" :src="company_logo" :alt="company_name + 'logo'">
            </div>
            <div class="form form--claim">
              <div class="field field--full-width">
                <label for="claim_profile"  class="label label--title">To claim this profile, register with an email address containing <b>{{ company_url}}</b> domain. Then check your inbox for a confirmation email</label>
                <input class="input" autocomplete="off" id="claim_profile"  name="email" placeholder="Enter email address"  type="email">
              </div>
              <div class="field field--full-width">
                <button class="btn btn--large btn--full-width btn--primary">
                  <span class="btn__text">Claim Profile</span>
                </button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
import icon from './Icon';
export default {
  name: 'modalclaim',
  data () {
    return {
      data: {},
      state: {
        loading: false,
        active: false,
      }
    }
  },
  computed: {
  },
  mounted() {
  },
  props: ['company_name', 'company_url', 'company_logo'],
  methods: {
    showModal(){
      this.state.active = true;  
    },
    closeModal(){
      this.state.active = false;  
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
