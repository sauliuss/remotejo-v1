<template>
  <transition name="fade">
    <div class="outlay" @click.self="close()" v-show="slug !== ''">

      <transition name="slideright">
        <div v-if="slug !== ''" class="dialog dialog--semi-full">
          <button href="#" class="btn-close btn-close--modal" @click="close()">
            <icon :name="'close--large'"></icon>
          </button>
          <div class="hero">
            <a :href="'/company/'+company.slug" target="_blank" class="btn btn--small btn--new-window">Open in a new window</a>
            <div class="hero__container hero__container--dialog">
                <div class="avatar">
                        <img v-if="company.logo !== null" class="avatar__img avatar__img--hero is-company is-large" :src="'/'+company.logo" :alt="company.name + ' logo'">
                       <img v-else class="avatar__img avatar__img--hero is-company is-large" src="/img/default.svg" alt="No image icon"> 
                </div>
                <div class="overview">
                    <div class="overview__header">
                        <div class="company-title company-title--large">
                          {{ company.name }}
                          <span v-if="company.is_verified" class="badge badge--verified">
                              <svg class="icon icon-verified" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-verified"></use>
                              </svg>                
                          </span>
                        </div>
<!--                         <modalclaim v-else :company_logo="`{!!(isset($company->logo) && !empty($company->logo)) ? asset($company->logo) : asset('img/default.svg') !!}`" :company_url="`{!! $meta_data['company_url_host'] !!}`" :company_name="`{!!  $company->name !!}`"></modalclaim> -->

                    </div>
                    <div class="overview__links">
                        <div class="overview__links__item">
                            <a target="_blank" :href="company.url">
                                <span>
                                    {{ company.url_host }}
                                </span>
                            </a>
                        </div>

                        <div v-if="company.twitter !== null" class="overview__links__item">
                            <a class="" target="_blank" :href="company.twitter">
                                <svg class="icon icon-social" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-twitter"></use>
                                </svg>
                            </a>
                        </div>

                        <div v-if="company.facebook !== null" class="overview__links__item">
                            <a class="" target="_blank" :href="company.facebook">
                                <svg class="icon icon-social" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-facebook"></use>
                                </svg>
                            </a>
                        </div>
                        
                        <div v-if="company.github !== null" class="overview__links__item">
                            <a class="" target="_blank" :href="company.github">
                                <svg class="icon icon-social" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-github"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
          </div>
            <div class="content content--company content--dialog">
                <div class="sidebar sidebar--left">
                    <div class="list">
                        <div v-if="company.remote_level !== null" class="list__item">
                            <div v-if="company.remote_level == 0" class="title">
                                <svg class="icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-remote-friendly"></use>
                                </svg>
                                <span>
                                    {{ company.remote_level_name }}
                                </span>
                            </div>
                            <div v-else-if="company.remote_level == 1"  class="title">
                                <svg class="icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-remote-first"></use>
                                </svg>
                                <span>
                                    {{ company.remote_level_name }}
                                </span>
                            </div>
                            <div v-else-if="company.remote_level == 2" class="title">
                                <svg class="icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-fully-remote"></use>
                                </svg>
                                <span>
                                    {{ company.remote_level_name }}
                                </span>
                            </div>
                        </div>

                        <div v-if="company.headquaters !== null" class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-location"></use>
                                </svg>
                                <span>
                                    {{ company.headquaters }}
                                </span>
                            </div>
                        </div>
                        <div v-if="company.type !== null" class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-type"></use>
                                </svg>
                                <span>
                                    {{ company.type }}
                                </span>
                            </div>
                        </div>

                        <div v-if="company.size !== null" class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-people"></use>
                                </svg>
                                <span>
                                    {{ company.size_alias }}
                                </span>
                            </div>
                        </div>

                        <div v-if="company.industries !== undefined && company.industries.length > 0" class="list__item">
                            <div class="title">
                                <svg class="icon icon-company-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-tag"></use>
                                </svg>
                                <span v-for="(industry, key) in company.industries">
                                  <a :href="'/industries/'+industry.slug">
                                    {{ industry.name }}
                                  </a>
                                  <span class="divider">&nbsp;&#183;&nbsp;</span>
                                </span>
                            </div>
                          </div>
<!--                         <div class="list__item">
                            <span class="title title--disclaimer">
                                This profile was updated last time by {{ $company->is_verified ? $company->name : 'community' }} {{$company->updated_at->diffForHumans()}}
                            </span>
                         </div> -->
                     </div>
                </div>          
          </div>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
import icon from './Icon';
export default {
  name: 'dialogcompany',
  data () {
    return {
      company: '',
      state: {
        loading: false,
        active: false,
      }
    }
  },
  computed: {
  },
  mounted(){
    // Focuses on the modal, so that keyup.esc can be triggered
    document.addEventListener("keydown", (e) => {
        if (e.keyCode == 27) {
            this.close();
        }
    });
  },
  watch: {
    slug: {
      deep: true,
      handler(){
        if(this.slug !== undefined && this.slug !== ''){
          this.submit();
        }
      }
    }
  },
  props: {
    slug: {
        type: [Number, String],
        required: true,
      }
  },
  methods: {
    close(){
      this.$emit('close', '');
    },
    submit(){
      axios
      .post('/api/v1/company/'+this.slug)
      .then(response => {
        console.log(response.data.data);
        this.company = response.data.data;





      })
      .catch(error => {

      })
    }
  },
  components: {icon}
}
</script>
