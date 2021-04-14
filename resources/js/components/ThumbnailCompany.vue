<template>
  <div>
        <div class="list">
          <a @click.prevent="showCompanyDialog(company.slug)" :href="'/company/'+company.slug" class="company-thumbnail">
            <div class="avatar avatar--thumbnail">
              <img v-if="company.logo !== null" class="avatar__img avatar__img--medium is-company" :src="'/'+company.logo" :alt="company.name + ' logo'">
             <img v-else :src="'/img/default.svg'"  class="avatar__img avatar__img--medium is-company is-small" alt="No image">
            </div>
            <div class="company-info">
                <span class="company-title company-title--thumbnail">
                  {{ company.name }}
                    <span v-if="company.is_claimed" class="badge badge--verified">
                        <svg class="icon icon-verified" aria-hidden="true">
                            <use xlink:href="/img/svg_symbols.svg#icon-verified"></use>
                        </svg>                
                    </span>
                </span>
                <ul v-if="company.industries.length > 0" class="grid grid--micro">
                  <li class="grid__item grid__item--micro">
                    <svg class="icon-company-info icon-company-info--no-margin" aria-hidden="true">
                        <use xlink:href="img/svg_symbols.svg#icon-tag"></use>
                    </svg>
                  </li>
                  <li v-for="(industry, index) in company.industries" class="grid__item grid__item--micro">
                    <div class="title title--small title--tag">
                      <a :href="'/industries/'+industry.slug">{{ industry.name }}</a>
                    </div>
                  </li>
                </ul>

                <span class="company__description">
                    {{ company.description_short }}
                </span>
<!--                 <ul class="grid grid--btm-mg-sm">
                  <li v-if="typeof company.remote_level !== 'undefined' && company.remote_level == 0" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon-company-info" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-remote-friendly"></use>
                      </svg>
                      <span>
                          {{ settings.remote_level[0] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.remote_level !== 'undefined' && company.remote_level == 1" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon-company-info" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-remote-first"></use>
                      </svg>
                      <span>
                          {{ settings.remote_level[1] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.remote_level !== 'undefined' && company.remote_level == 2" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon-company-info" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-fully-remote"></use>
                      </svg>
                      <span>
                          {{ settings.remote_level[2] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.type !== 'undefined' && company.type !== null" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon-company-info" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-type"></use>
                      </svg>
                      <span>
                          {{ settings.type[company.type] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.size !== 'undefined'" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon-company-info" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-people"></use>
                      </svg>
                      <span>
                          {{ settings.size[company.size] }}
                      </span>
                    </div>
                  </li>
                </ul> -->
<!--                 <div class="container" v-if="company.hiring_regions.length > 0 && (filters.timezones.length > 0 || filters.countries.length > 0)">
                  <span class="title title--info">Hires remotely</span>
                  <ul class="grid grid--align-center" v-if="company.hiring_regions.length > 0">
                     <li v-for="hiring_region in company.hiring_regions" v-if="getIds(filters.countries).indexOf(hiring_region.id) > -1 || getIds(filters.timezones).indexOf(hiring_region.id) > -1" class="grid__item">
                       <div v-if="hiring_region.type == 'timezone'" class="thumbnail thumbnail--badge"> 
                        {{ 'UTC '+hiring_region.name }}
                       </div>
                       <div v-else class="thumbnail thumbnail--badge"> 
                         {{ hiring_region.name }}
                       </div>
                     </li>
                  </ul>
                </div>
                <div class="container" v-if="company.tools.length > 0 && filters.tools.length > 0">
                  <span class="title title--info">Stack includes</span>
                  <ul class="grid grid--align-center">
                     <li v-for="tool in company.tools" v-if="getIds(filters.tools).indexOf(tool.id) > -1" class="grid__item">
                         <div class="thumbnail thumbnail--badge"> 
                            <img :src="tool.logo" alt="">
                            {{ tool.name }}
                         </div>
                     </li>
                  </ul>
                </div> -->
            </div>
          </a>
        </div>
        <dialogcompany @close="onDialogClose()" :slug="dialog_company" :settings="settings"></dialogcompany>
  </div>
</div>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
import dialogcompany from '../components/DialogCompany.vue';

export default {
  name: 'thumbnailcompany',
  data () {
    return {
      current_url: '',
      pagination: {
      },
      state: {
        loading: false,
      },
      dialog_company: ''
    }
  },
  computed: {
  },
  watch: {

  },
  
  mounted() {
    
  },
  props: {
    settings: Object,
    company: Object,
  },
  methods: {
    showCompanyDialog(slug){
      var body = document.body;

      this.current_url = window.location.href;
      body.classList.add("no-scroll");
      history.replaceState(null, '', '/company/'+slug);
      this.dialog_company = slug;
    },
    onDialogClose(){
      this.dialog_company = '';
      var body = document.body;
      body.classList.remove("no-scroll");
      history.replaceState(null, '', this.current_url);
    },
  },
  components: { dialogcompany}
}
</script>
