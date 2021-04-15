<template>
  <transition name="fade">
    <div class="outlay" @click.self="close()" v-show="slug !== ''">

      <transition name="slideright">
        <div v-if="slug !== ''" class="dialog-wrapper">
          <div class="dialog dialog--semi-full">
            <button class="btn-close btn-close--modal" @click="close()">
              <icon :name="'close--large'"></icon>
            </button>

            <div class="hero">
              <a :href="'/company/'+company.slug" target="_blank" class="btn btn--small btn--new-window">
                <span>Open in a new window</span>
                <svg class="icon-open" aria-hidden="true">
                    <use xlink:href="/img/svg_symbols.svg#icon-open"></use>
                </svg>
              </a>
              <div class="hero__container hero__container--dialog">
                  <div class="avatar avatar--dialog" :class="{'is-loading': this.state.loading}">
                          <img v-if="company.logo !== null && !this.state.loading" class="avatar__img avatar__img--hero avatar__img--large is-company" :src="'/'+company.logo" :alt="company.name + ' logo'">
                         <img v-else-if="company.logo == null && !this.state.loading" class="avatar__img avatar__img--hero avatar__img--large is-company" src="/img/default.svg" alt="No image icon">
                         <span class="avatar "></span>
                  </div>
                  <div class="overview">
                      <div class="overview__header">
                          <div class="company-title company-title--large">
                            {{ company.name }}
                            <span v-if="company.is_claimed" class="badge badge--verified">
                                <svg class="icon icon-verified" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-verified"></use>
                                </svg>                
                            </span>
                          </div>
                          <modalclaim v-if="!company.is_claimed" :company_logo="company.logo" :company_url="company.url_host" :company_name="company.name"></modalclaim>

                      </div>
                      <div class="grid overview__info" :class="{'is-loading': this.state.loading}">
                          <div v-if="company.type !== null" class="grid__item">
                              <div class="title title--light">
                                  <svg class="icon icon--fill icon--left" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-type"></use>
                                  </svg>
                                  <span>
                                      {{ company.type_alias }}
                                  </span>
                              </div>
                          </div>

                          <div v-if="company.size !== null" class="grid__item">
                              <div class="title title--light">
                                  <svg class="icon icon--left icon--fill" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-people"></use>
                                  </svg>
                                  <span>
                                      {{ company.size_alias }}
                                  </span>
                              </div>
                          </div>

                          <div v-if="company.industries !== undefined && company.industries.length > 0" class="grid__item">
                              <div class="title title--light title--industries">
                                  <svg class="icon icon icon--fill icon--left" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tag"></use>
                                  </svg>
                                  <span>
                                    <a :href="'/industries/'+company.industries[0].slug">
                                      {{ company.industries[0].name }}
                                    </a>
                                  </span>
                                  <div v-if="company.industries.length > 1" class="btn--popup">
                                    <div class="popup--industries">
                                      <span v-for="(industry, key) in company.industries_popup">
                                        <a :href="'/industries/'+industry.slug">
                                          {{ industry.name }}
                                        </a>
                                      </span>
                                    </div>
                                    ...
                                  </div>
                              </div>
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
                                      <svg class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                          <use xlink:href="/img/svg_symbols.svg#icon-twitter"></use>
                                      </svg>
                                  </a>
                              </div>

                              <div v-if="company.facebook !== null" class="overview__links__item">
                                  <a class="" target="_blank" :href="company.facebook">
                                      <svg class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                          <use xlink:href="/img/svg_symbols.svg#icon-facebook"></use>
                                      </svg>
                                  </a>
                              </div>
                              
                              <div v-if="company.github !== null" class="overview__links__item">
                                  <a class="" target="_blank" :href="company.github">
                                      <svg class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                          <use xlink:href="/img/svg_symbols.svg#icon-github"></use>
                                      </svg>
                                  </a>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
            </div>
              <div class="content content--company content--dialog">
                  <div class="sidebar sidebar--left">
                      <ul class="list">
                          <li v-if="this.state.loading" class="list__item is-loading"></li>
                          <li v-if="this.state.loading" class="list__item is-loading"></li>
                          <li v-if="this.state.loading" class="list__item is-loading"></li>
                          <li v-if="company.remote_level !== null" class="list__item">
                            <div class="title">
                              <svg v-if="company.remote_level == 0" class="icon icon--fill icon--left" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-remote-friendly"></use>
                              </svg>
                              <svg v-else-if="company.remote_level == 1" class="icon icon--fill icon--left" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-remote-first"></use>
                              </svg>
                              <svg v-else-if="company.remote_level == 2" class="icon icon--fill icon--left" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-fully-remote"></use>
                              </svg>
                                <span>Remote level</span>
                            </div>
                            <ul class="list list--regions">
                              <li class="list__item list__item--regions has-tooltip">
                                {{ company.remote_level_name }}
                                <svg class="icon-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-info"></use>
                                </svg>
                                <span class="tooltip" role="tooltip">
                                  Only a small part of the team works remotely
                                </span>
                              </li>
                            </ul>
                          </li>

                          <li v-if="company.timezones.length !== 0 || company.regions.length !== 0" class="list__item">
                              <div class="title">
                                  <svg class="icon icon icon--fill icon--left" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-people"></use>
                                  </svg>
                                  <span>
                                    Hires remotely
                                  </span>
                              </div>
                              <ul class="list list--regions">
                                <li class="list__item list__item--regions" v-for="timezone in company.timezones">
                                  UTC{{ timezone.name }}
                                </li>
                                <li class="list__item list__item--regions" v-for="region in company.regions">
                                   <span>{{ region.emoji }}</span> <span>{{ region.name }}</span>
                                </li>
                              </ul>
                          </li>

                          <li v-if="company.headquaters !== null && company.headquaters !== 'null'" class="list__item">
                              <div class="title">
                                  <svg class="icon icon icon--fill icon--left" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-location"></use>
                                  </svg>
                                  <span>Headquaters</span>
                              </div>
                              <ul class="list list--regions">
                                <li class="list__item list__item--regions">
                                  {{ company.headquaters }}
                                </li>
                              </ul>
                          </li>

                          <li class="list__item">
                              <span class="title title--disclaimer">
                                  This profile was updated last time by {{ company.is_verified ? company.name : 'community' }} {{ company.updated_at}}
                              </span>
                           </li>
                           <li class="list__item">
                               <button class="btn btn--light btn--small btn-report">
                                <svg class="icon icon icon--fill icon--left" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-flag"></use>
                                </svg>
                                <span class="btn__text">Report this page</span>   
                               </button>
                            </li>
                       </ul>
                  </div>
                  <div class="main main--company">
                      <div class="main__section section">
                          <p v-if="!this.state.loading" class="company-description">{{ company.description_short }}</p>
                          <span v-if="this.state.loading" class="company-description is-loading"></span>
                      </div>
                      <div class="main__section section">
                          <h2 class="section__title">Jobs</h2>

                          <formalert :company_name="company.name"></formalert>

                          <div v-if="company.tools == undefined ||  company.tools.length == 0" class="section__body section__body--empty">
                            <h3 class="section__subtitle">Unfortunatelly, currently there is no available remote jobs at {{company.name}}</h3>

                          </div>
<!--                           <div v-else class="section__body" v-for="tools in company.tools">
                            <h3 class="section__subtitle">{{ tools[0].type.name }}</h3>
                            <div class="grid grid--tools">
                                    <div v-for="tool in tools" class="grid__item grid__item--tools">
                                        <a :href="'/stack/'+tool.slug" class="thumbnail thumbnail--tool">
                                            <img v-if="tool.logo !== null" class="thumbnail--tool__img" :src="'/'+tool.logo" :alt="tool.name + 'logo'">
                                            <img v-else class="thumbnail--tool__img" src="/img/default.svg" alt="No image icon">
                                            <span class="thumbnail--tool__name">{{tool.name }}</span>
                                        </a>
                                    </div>
                            </div>
                          </div> -->
                      </div>
                      <div v-if="company.tools.length !== 0" class="main__section section">
                          <h2 class="section__title">Stack</h2>
                          <div class="section__body" v-for="tools in company.tools">
                            <h3 class="section__subtitle">{{ tools[0].type.name }}</h3>
                            <div class="grid grid--tools">
                                    <div v-for="tool in tools" class="grid__item grid__item--tools">
                                        <a :href="'/stack/'+tool.slug" class="thumbnail thumbnail--tool">
                                            <img v-if="tool.logo !== null" class="thumbnail--tool__img" :src="'/'+tool.logo" :alt="tool.name + 'logo'">
                                            <img v-else class="thumbnail--tool__img" src="/img/default.svg" alt="No image icon">
                                            <span class="thumbnail--tool__name">{{tool.name }}</span>
                                        </a>
                                    </div>
                            </div>
                          </div>
                      </div>
                      <div v-if="company.benefits.length !== 0" class="main__section section">
                          <h2 class="section__title">Benefits</h2>

                          <div class="grid">
                            <div v-if="company.benefits.health !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg class="icon-benefit" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-health"></use>
                                </svg>
                                <span>Health and Welness</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.health">
                                  <svg class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>

                            <div v-if="company.benefits.compensation !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg class="icon-benefit" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-money"></use>
                                </svg>
                                <span>Compensation</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.compensation">
                                  <svg class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>

                            <div v-if="company.benefits.timeoff !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg class="icon-benefit" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-timeoff"></use>
                                </svg>
                                <span>Work and Life</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.timeoff">
                                  <svg class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>

                            <div v-if="company.benefits.other !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg class="icon-benefit" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-other"></use>
                                </svg>
                                <span>Other</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.other">
                                  <a :href="benefit.slug" class="link link--list">
                                    <svg class="icon icon--left icon-tick" aria-hidden="true">
                                        <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                    </svg>
                                    {{ benefit.name }}
                                  </a>
                                </li>
                              </ul>
                            </div>
<!--                             <div v-for="benefit in company.benefits" class="grid__item grid__item--benefits">
                                <a :href="'/benefits/' + benefit.slug" class="thumbnail thumbnail--benefit">
                                    <div class="icon-container">
                                        <svg v-if="benefit.parent_id == 1" class="icon-benefit" aria-hidden="true">
                                                <use xlink:href="/img/svg_symbols.svg#icon-health"></use>

                                        </svg>
                                        <svg v-else-if="benefit.parent_id == 7" class="icon-benefit" aria-hidden="true">
                                                <use xlink:href="/img/svg_symbols.svg#icon-money"></use>
                                        </svg>
                                        <svg v-else-if="benefit.parent_id == 12" class="icon-benefit" aria-hidden="true">
                                                <use xlink:href="/img/svg_symbols.svg#icon-timeoff"></use>
                                        </svg>
                                        <svg v-else-if="benefit.parent_id == 19" class="icon-benefit" aria-hidden="true">
                                                <use xlink:href="/img/svg_symbols.svg#icon-other"></use>
                                        </svg>

                                        <span v-if="benefit.parent_id == 1" class="icon-health"></span>
                                        <span v-if="benefit.parent_id == 7" class="icon-health"></span>
                                        <span v-if="benefit.parent_id == 12" class="icon-health"></span>
                                        <span v-if="benefit.parent_id == 19" class="icon-health"></span>
                                    </div>
                                    <span class="name">
                                        {{ benefit.name }}
                                    </span>
                                </a>
                              </div> -->
                          </div>
                      </div>
                  </div>
 <!--                  <div class="sidebar sidebar--right">
                    <formnotification :subscribers_count="12" :categories="settings.job_categories" :selected="settings.notification" :company_name="company.name"></formnotification>
                  </div>   -->   
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
import modalclaim from './ModalClaim';
import formalert from './FormAlert';
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
      },
    settings: {
      type: Object
    }
  },
  methods: {
    sliceIndustries(industries){
      return industries.shift();
    },
    close(){
      this.$emit('close', '');
    },
    submit(){
      this.state.loading = true;

      axios
      .post('/api/v1/company/'+this.slug)
      .then(response => {
        this.state.loading = false;
        this.company = response.data.data;

        console.log(this.company.benefits);

        window.addEventListener('load', function(){
          console.log("okkk");
        });

        // Creates an array of industries to show in a popup
        if(this.company.industries.length > 1){
          this.company.industries_popup = this.company.industries.slice(1);
        }

      })
      .catch(error => {

      })
    }
  },
  components: {icon, modalclaim, formalert}
}
</script>
