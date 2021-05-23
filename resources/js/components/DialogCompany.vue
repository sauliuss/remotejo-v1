<template>
  <transition name="fade">
    <div class="outlay" @click.self="close()" v-show="slug !== ''">

      <transition name="slideright">
        <div v-if="slug !== ''" class="dialog-wrapper">
          <div v-if="this.state.loading" class="dialog dialog--semi-full">
           <button class="btn-close btn-close--dialog" @click="close()">
             <icon :name="'close--large'"></icon>
           </button>

           <div class="hero">
             <div class="hero__container hero__container--dialog">
                 <div class="avatar avatar--dialog is-loading">
                        <span class="avatar "></span>
                 </div>
                 <div class="overview">
                   <div class="loading loading--md"></div>
                   <div class="loading loading--sm loading--margin-no"></div>
                 </div>
             </div>
           </div>
             <div class="content content--company content--dialog">
                 <div class="sidebar sidebar--left">
                     <ul class="list list--remote-info">
                         <li class="loading loading--sm"></li>
                         <li class="loading loading--sm"></li>
                         <li class="loading loading--sm"></li>
                         <li class="loading loading--sm"></li>
                         <li class="loading loading--sm"></li>
                      </ul>
                 </div>
                 <div class="main main--company main--company--about">
                     <div class="loading loading--xl loading--margin-lg"></div>
                 </div>
                 <div class="main main--company">
                   <div class="loading loading--lg"></div>
                   <div class="loading loading--xl loading--margin-lg"></div>
                   <div class="loading loading--lg"></div>
                   <div class="loading loading--xl loading--margin-lg"></div>
                   <div class="loading loading--lg"></div>
                   <div class="loading loading--xl loading--margin-lg"></div>
                 </div>
             </div>
          </div>
          <div v-if="!this.state.loading" class="dialog dialog--semi-full">
            <button class="btn-close btn-close--dialog" @click="close()">
              <icon :name="'close--large'"></icon>
            </button>

            <div class="hero">
              <a :href="'/company/'+company.slug" target="_blank" class="btn btn--small btn--new-window">
                <svg role="img" class="icon-open" aria-hidden="true">
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
                    <div v-if="this.state.loading" class="loading loading--md"></div>
                    <div v-if="!this.state.loading" class="overview__header">
                        <div class="company-title company-title--large">
                          {{ company.name }}
                          <span v-if="company.is_claimed" class="badge badge--verified">
                              <svg role="img" class="icon icon-verified" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-verified"></use>
                              </svg>                
                          </span>
                        </div>
                        <modalclaim v-if="!company.is_claimed" :company_logo="company.logo" :company_url="company.url_host" :company_name="company.name"></modalclaim>
                        <!-- <div class="rating" v-if="company.rating !== undefined ||  company.rating.length !== 0"> -->
                        <div class="overview__rating">
                          <div class="overview__rating__header">
                            <span class="overview__rating__number">4.1</span>
                            <svg role="img" class="overview__rating__img" aria-hidden="true">
                                <use xlink:href="/img/ratings.svg#rating-1"></use>
                            </svg>
                          </div>
                          <div class="overview__rating__body">
                            <span class="title title--light">10 reviews</span>
                          </div>
                        </div>
                    </div>
                    <div v-if="this.state.loading" class="loading loading--sm loading--margin-no"></div>
                    <div v-if="!this.state.loading" class="overview__info">
                      <div class="grid">
                          <div v-if="company.type !== null" class="grid__item">
                              <div class="title title--light">
                                  <svg role="img" class="icon icon--fill icon--left" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-type"></use>
                                  </svg>
                                  <span>
                                      {{ company.type_alias }}
                                  </span>
                              </div>
                          </div>

                          <div v-if="company.size !== null" class="grid__item">
                              <div class="title title--light">
                                  <svg role="img" class="icon icon--left icon--fill" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-people"></use>
                                  </svg>
                                  <span>
                                      {{ company.size_alias }}
                                  </span>
                              </div>
                          </div>

                          <div v-if="company.founding_years !== null" class="grid__item">
                              <div class="title title--light">
                                  <svg role="img" class="icon icon--left icon--fill" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-founded"></use>
                                  </svg>
                                  <span>
                                      {{ company.founding_years }}
                                  </span>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
              <div class="content content--company content--dialog">
                  <div class="sidebar sidebar--left">
                      <ul class="list list--remote-info">
                          <li class="list__item list__item--remote-info">
                            <div class="title">
                              <svg role="img" v-if="company.remote_level == 0" class="icon icon--fill icon--left icon-level" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-level--friendly"></use>
                              </svg>
                              <svg role="img" v-else-if="company.remote_level == 1" class="icon icon--fill icon--left icon-level" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-level--first"></use>
                              </svg>
                              <svg role="img" v-else-if="company.remote_level == 2" class="icon icon--fill icon--left icon-level" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-level--all"></use>
                              </svg>
                              <span>Remote level</span>
                            </div>
                            <ul class="list list--regions">
                              <li class="list__item list__item--regions has-tooltip">
                                {{ company.remote_level_name }}
                                <svg role="img" class="icon-info" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-info"></use>
                                </svg>
                                <tooltip :type="company.remote_level"></tooltip>
                              </li>
                            </ul>
                          </li>

                          <li v-if="company.timezones.length !== 0 || company.regions.length !== 0" class="list__item list__item--remote-info">
                              <div v-if="!this.state.loading">
                                <div class="title">
                                    <svg role="img" class="icon icon icon--fill icon--left" aria-hidden="true">
                                        <use xlink:href="/img/svg_symbols.svg#icon-people"></use>
                                    </svg>
                                    <span>
                                      Hires remotely
                                    </span>
                                </div>
                                <ul class="list list--regions">
                                  <li class="list__item list__item--regions" v-for="timezone in company.timezones">
                                    GMT{{ timezone.name }}<span class="middot">&nbsp;&middot;&nbsp;</span>
                                  </li>
                                  <li class="list__item list__item--regions" v-for="region in company.regions">
                                     <span>{{ region.emoji }}</span> <span>{{ region.name }}</span>
                                     <span class="middot">&nbsp;&middot;&nbsp;</span>
                                  </li>
                                </ul>
                              </div>
                          </li>

                          <li v-if="company.headquaters !== null && company.headquaters !== 'null' && !this.state.loading" class="list__item list__item--remote-info">
                              <div class="title">
                                  <svg role="img" class="icon icon icon--fill icon--left" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-location"></use>
                                  </svg>
                                  <span>Office locations</span>
                              </div>
                              <ul class="list list--regions">
                                <li class="list__item list__item--regions">
                                  {{ company.headquaters }}
                                </li>
                              </ul>
                          </li>
                       </ul>
                  </div>
                  <div class="sidebar--footer"  v-if="!this.state.loading">
                    <span class="title title--disclaimer">
                        This profile was updated last time by {{ company.is_verified ? company.name : 'community' }} {{ company.updated_at}}
                    </span>
                    <modalreport></modalreport>
                  </div>
                  <div class="main main--company main--company--about">
                      <div v-if="this.state.loading" class="loading loading--xl loading--margin-lg"></div>
                      <ul v-if="company.industries !== undefined && company.industries.length > 0 && !this.state.loading" class="industries">
                        <li v-for="(industry, key) in company.industries">
                          <a class="link link--industry" :href="'/industries/'+industry.slug">{{industry.name}}</a><span class="middot">&nbsp;&middot;&nbsp;</span>
                        </li>
                      </ul>
                      <p v-if="company.description_full !== undefined && company.description_full !== null && !this.state.loading" class="company-description">{{ company.description_full }}</p>
                      <p v-else-if="!this.state.loading" class="company-description">{{ company.description_short }}</p>
                      <div v-if="!this.state.loading" class="grid grid--micro grid--links">
                          <div class="grid__item grid__item--micro company-link">
                            <a class="link-url-icon" target="_blank" :href="company.url">
                              <svg role="img" class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-url"></use>
                              </svg>
                            </a>
                          </div>

                          <div v-if="company.twitter !== null" class="grid__item grid__item--micro company-link">
                              <a class="" target="_blank" :href="company.twitter">
                                  <svg role="img" class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-twitter"></use>
                                  </svg>
                              </a>
                          </div>

                          <div v-if="company.facebook !== null" class="grid__item grid__item--micro company-link">
                              <a class="" target="_blank" :href="company.facebook">
                                  <svg role="img" class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-facebook"></use>
                                  </svg>
                              </a>
                          </div>
                          
                          <div v-if="company.github !== null" class="grid__item grid__item--micro company-link">
                              <a class="" target="_blank" :href="company.github">
                                  <svg role="img" class="icon icon--fill icon--left icon-social" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-github"></use>
                                  </svg>
                              </a>
                          </div>
                      </div>
                  </div>
                  <div class="main main--company">
                      <div class="main__section section">
                          <h2 class="section__title">Jobs</h2>

                          <formalert :company_name="company.name" :job_categories="company.job_categories"></formalert>

                          <div v-if="company.jobs == undefined ||  company.jobs.length == 0" class="section__body section__body--empty">
                            <h3 class="section__subtitle section__subtitle--empty">Yikes! There is no open remote roles at {{company.name}} at the moment</h3>
                            <p>Set a job alert and receive the latest remote jobs from {{company.name}}. No spam. Just opportunities in your inbox.</p>
                          </div>
                          <div v-else class="section__body">
                            <div v-for="job in company.jobs">
                              <a :href="/job/+job.job_slug">{{job.job_title}}</a>
                            </div>
                          </div>
                      </div>
                      <div  class="main__section section">
                          <h2 class="section__title">Reviews</h2>

                         <div class="section__body">

                            <ul class="reviews">
                              <li class="review">
                                <div class="review__header">
                                  <!-- <span>By <b>current employee</b><span class="middot">&nbsp;&middot;&nbsp;</span><span>Senior software engineer</span></span>
                                  <span class="middot">&nbsp;&middot;&nbsp;</span> --><span>Posted 2 days ago</span>
                                  <li class="review__rating">
                                    <span class="review__rating__number">4.1</span>
                                    <svg role="img" class="review__rating__img" aria-hidden="true">
                                        <use xlink:href="/img/ratings.svg#rating-5"></use>
                                    </svg>
                                  </li>
                                </div>
                                <div class="review__body">
                                  <h3 class="review__title">"Amazing company to work in. All remote team with a great product and culture"</h3>
                                  <ul class="ratings">
                                    <li class="rating">
                                      <div class="rating__header">
                                        <h3 class="rating__title">Remote culture</h3>
                                        <svg role="img" class="rating__img" aria-hidden="true">
                                            <use xlink:href="/img/ratings.svg#rating-5"></use>
                                        </svg>
                                      </div>
                                      <span class="rating__comment">Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro, molestias. Ea eum dicta amet impedit placeat autem tenetur eius. Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro, molestias. Ea eum dicta amet impedit placeat autem tenetur eius.</span>
                                    </li>
                                    <li class="rating">
                                      <div class="rating__header">
                                        <h3 class="rating__title">Career growth</h3>
                                        <svg role="img" class="rating__img" aria-hidden="true">
                                            <use xlink:href="/img/ratings.svg#rating-2"></use>
                                        </svg>
                                      </div>
                                      <span class="rating__comment">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim, voluptatum magni suscipit expedita? Eius voluptates quo consequuntur maiores dignissimos, odio error, hic at a nisi fuga praesentium neque, eveniet, iste?</span>
                                    </li>
                                    <li class="rating">
                                      <div class="rating__header">
                                        <h3 class="rating__title">Life and work balance</h3>
                                        <svg role="img" class="rating__img" aria-hidden="true">
                                            <use xlink:href="/img/ratings.svg#rating-4"></use>
                                        </svg>
                                      </div>
                                    </li>
                                    <li class="rating">
                                      <div class="rating__header">
                                        <h3 class="rating__title">Compensation and benefits</h3>
                                        <svg role="img" class="rating__img" aria-hidden="true">
                                            <use xlink:href="/img/ratings.svg#rating-1"></use>
                                        </svg>
                                      </div>
                                    </li>
                                    <li class="rating">
                                      <div class="rating__header">
                                        <h3 class="rating__title">Management</h3>
                                        <svg role="img" class="rating__img" aria-hidden="true">
                                            <use xlink:href="/img/ratings.svg#rating-3"></use>
                                        </svg>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                              </li>
                            </ul>
                          </div>
                          <div class="section__footer">
                            <button class="btn btn--light btn--medium btn--load-more">
                              <span class="btn__text">Show all reviews</span>   
                            </button>
                          </div>
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
                                <svg role="img" class="icon-benefit" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-health"></use>
                                </svg>
                                <span>Health and Welness</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.health">
                                  <svg role="img" class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>

                            <div v-if="company.benefits.compensation !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg role="img" class="icon-benefit" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-money"></use>
                                </svg>
                                <span>Compensation</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.compensation">
                                  <svg role="img" class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>

                            <div v-if="company.benefits.timeoff !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg role="img" class="icon-benefit" aria-hidden="true">
                                  <use xlink:href="/img/svg_symbols.svg#icon-timeoff"></use>
                                </svg>
                                <span>Work and Life</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.timeoff">
                                  <svg role="img" class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>

                            <div v-if="company.benefits.other !== undefined" class="grid__item grid__item--benefits">
                              <h3 class="section__subtitle">
                                <svg role="img" class="icon-benefit" aria-hidden="true">
                                    <use xlink:href="/img/svg_symbols.svg#icon-other"></use>
                                </svg>
                                <span>Other</span>
                              </h3>
                              <ul class="list list--benefits">
                                <li class="list__item list__item--benefits" v-for="benefit in company.benefits.other">
                                  <svg role="img" class="icon icon--left icon-tick" aria-hidden="true">
                                      <use xlink:href="/img/svg_symbols.svg#icon-tick"></use>
                                  </svg>
                                  {{ benefit.name }}
                                </li>
                              </ul>
                            </div>
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
import modalreport from './ModalReport';
import formalert from './FormAlert';
import tooltip from './Tooltip';
export default {
  name: 'dialogcompany',
  data () {
    return {
      company: '',
      reviews_list: [],
      state: {
        loading: true,
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
    this.createReviewsList();
  },
  updated(){

    // if(this.data !== ''){
    //   var total_images = this.$el.querySelectorAll(".dialog img");
    //   var images_loaded = 0;

    //   function imageLoaded(){
    //     images_loaded++
    //     if(images_loaded == total_images.length){
    //       console.log("Images are loaded");
    //     }
    //   }

    //   for (var i = 0; i < total_images.length; i++) {

    //     var fake_image = document.createElement('img');

    //     fake_image.addEventListener('load', (e) => {
    //       imageLoaded()
    //     });

    //     fake_image.setAttribute('src', total_images[i].getAttribute('src'));
    //   }
    // }

  },
  watch: {
    slug: {
      deep: true,
      handler(){
        if(this.slug !== undefined && this.slug !== ''){
          this.submit();
        }
      }
    },
    data: {

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
    expandReview(review_id){

    },

    createReviewsList(){
      let reviews = ['s','d'];
      reviews.forEach(el => {
        this.reviews_list.push({el, active: false});
      });
    },
    sliceIndustries(industries){
      return industries.shift();
    },
    close(){
      this.company = '';
      this.$emit('close', '');
    },
    submit(){
      this.state.loading = true;
      axios
      .post('/api/v1/company/'+this.slug)
      .then(response => {
        this.company = response.data.data;
        this.state.loading = false;
      })
      .catch(error => {

      })
    }
  },
  components: {icon, modalclaim, formalert, tooltip, modalreport}
}
</script>
