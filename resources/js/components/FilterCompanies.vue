<template>
  <div>
  <div class="content content--companies" :class="{'content--pushed': dialog_company !== ''}">
      <div class="sidebar sidebar--companies">
          <form class="form form-company">
            <h3 class="title-filter">Filter by <b>remote level</b></h3>
            <section class="form__section">
              <inputfilter @selection="onRemoteLevelSelection" :title="'remote level'" :options="settings.remote_level"></inputfilter>
            </section>
            <h3 class="title-filter">Filter by <b>hiring regions</b></h3>
            <section class="form__section">
              <inputtimezones :selected="[]" :type="'editor-timezones__body--small'" :options="settings.hiring_regions.timezones" @selection="onTimezoneSelection" :company_id="12"></inputtimezones>
              <inputcountries :selected="[]" :options="settings.hiring_regions.regions" @selection="onRegionSelection" :company_id="0"></inputcountries>
            </section>
            <h3 class="title-filter">Filter by <b>tools and tech stack</b></h3>
            <section class="form__section">
              <inputtools @selection="onToolSelection" :company_tools="[]"></inputtools>
            </section>
            <h3 class="title-filter">Filter by <b>industry</b></h3>
            <section class="form__section">
              <inputdropdowntags @selection="onIndustrySelection" :options="settings.industries" :selected="[]" :placeholder="'Pick an industry'">
                 <span></span>
              </inputdropdowntags>
            </section>
            <h3 class="title-filter">Filter by <b>company size</b></h3>
            <section class="form__section">
              <inputfilter @selection="onSizeSelection" :title="'size'" :options="settings.size"></inputfilter>
            </section>
          </form>
          <span class="dummy"></span>
      </div>
      <div class="main main--companies">
        <div class="main__header">Found <b>{{pagination.total }}</b> {{pagination.total == 1 ? 'company' : 'companies'}}</div>
        <div class="list">
          <div class="empty-state" v-if="companies.length == 0">
            No companies found
          </div>
          <a @click.prevent="showCompanyDialog(company.slug)" v-for="company in companies" :href="'/company/'+company.slug" class="company-thumbnail">
            <div class="avatar avatar--thumbnail">
              <img v-if="company.logo !== null" class="avatar__img avatar__img--medium is-company" :src="company.logo" :alt="company.name + ' logo'">
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
                <ul v-if="company.industries !== undefined && company.industries.length > 0" class="industries industries--thumbnail">
                  <li v-for="(industry, key) in company.industries">
                    <a class="link link--industry" :href="'/industries/'+industry.slug">{{industry.name}}</a>&nbsp;<span class="middot">&middot;</span>&nbsp;  
                  </li>
                </ul>

                <span class="company__description">
                    {{ company.description_short }}
                </span>
                <ul class="grid grid--btm-mg-sm">
                  <li v-if="typeof company.remote_level !== 'undefined' && company.remote_level == 0" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon--fill icon--left" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-remote-friendly"></use>
                      </svg>
                      <span>
                          {{ settings.remote_level[0] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.remote_level !== 'undefined' && company.remote_level == 1" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon--fill icon--left" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-remote-first"></use>
                      </svg>
                      <span>
                          {{ settings.remote_level[1] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.remote_level !== 'undefined' && company.remote_level == 2" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon--fill icon--left" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-fully-remote"></use>
                      </svg>
                      <span>
                          {{ settings.remote_level[2] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.type !== 'undefined' && company.type !== null" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon--fill icon--left" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-type"></use>
                      </svg>
                      <span>
                          {{ settings.type[company.type] }}
                      </span>
                    </div>
                  </li>

                  <li v-if="typeof company.size !== 'undefined'" class="grid__item">
                    <div class="title title--small">
                      <svg class="icon icon--fill icon--left" aria-hidden="true">
                          <use xlink:href="img/svg_symbols.svg#icon-people"></use>
                      </svg>
                      <span>
                          {{ settings.size[company.size] }}
                      </span>
                    </div>
                  </li>
                </ul>
                <div class="container" v-if="company.hiring_regions.length > 0 && (filters.timezones.length > 0 || filters.countries.length > 0)">
                  <span class="title title--info">Hires remotely</span>
                  <ul class="grid grid--align-center" v-if="company.hiring_regions.length > 0">
                     <li v-for="hiring_region in company.hiring_regions" v-if="getIds(filters.countries).indexOf(hiring_region.id) > -1 || getIds(filters.timezones).indexOf(hiring_region.id) > -1" class="grid__item">
                       <div v-if="hiring_region.type == 'timezone'" class="thumbnail thumbnail--badge"> 
                        {{ 'GMT '+hiring_region.name }}
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
                </div>
            </div>
          </a>
        </div>
        <div class="main__footer" v-if="pagination.last_page > 1">
          <div class="pagination-container">
            <pagination :pages="pagination" @submit="submit"></pagination>
          </div>
        </div>
      </div>
  </div>
  <dialogcompany @close="onDialogClose()" :slug="dialog_company" :settings="settings"></dialogcompany>
</div>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
import inputfilter from '../components/InputFilter';
import inputtools from '../components/InputTools';
import inputcountries from '../components/InputCountries';
import inputtimezones from '../components/InputTimezones';
import inputbenefits from '../components/InputBenefits';
import inputcompanytype from '../components/InputCompanyType';
import inputdropdowntags from '../components/InputDropdownTags';
import inputlocation from '../components/InputLocation';
import dialogcompany from '../components/DialogCompany.vue';
import pagination from '../components/Pagination.vue';

var qs = require('qs');

export default {
  name: 'filtercompanies',
  data () {
    return {
      settings: this.options,
      companies: '',
      pagination: {
      },
      filters: {
        timezones: [],
        countries: [],
        tools: [],
        industries: [],
        size: [],
        remote_level: [],
        type: [],
      },
      state: {
        loading: false,
      },
      filter: false,
      dialog_company: ''
    }
  },
  computed: {
  },
  watch: {
    filters: {
      deep: true,
      handler(){
        this.submit();
      }
    }
  },
  
  mounted() {
    
  },
  props: {
    options: Object
  },
  methods: {
    showCompanyDialog(slug){
      var body = document.body;
      body.classList.add("no-scroll");
      // history.replaceState(null, '', '/company/'+slug);
      this.dialog_company = slug;
    },
    getIds(array){
      var ids = [];
      for (var i = 0; i < array.length; i++) {
          ids.push(array[i].id);
      }

      return ids;
    },
    submit(page_number){

      this.state.loading = false;

      if(page_number !== undefined){
        window.scrollTo({top: 0, behavior: 'smooth'});
      }

      var hiring_regions_ids = [];
      for (var i = 0; i < this.filters.timezones.length; i++) {
          hiring_regions_ids.push(this.filters.timezones[i].id);
      }
      for (var i = 0; i < this.filters.countries.length; i++) {
          hiring_regions_ids.push(this.filters.countries[i].id);
      }
      var tool_ids = [];
      for (var i = 0; i < this.filters.tools.length; i++) {
          tool_ids.push(this.filters.tools[i].id);
      }

      var industry_ids = [];
      for (var i = 0; i < this.filters.industries.length; i++) {
          industry_ids.push(this.filters.industries[i].id);
      }

      axios
      .get('api/v1/companies', {
        params: {
          hiring_regions: hiring_regions_ids,
          tools: tool_ids,
          industries: industry_ids,
          remote_level: this.filters.remote_level,
          size: this.filters.size,
          type: this.filters.type,
          page: page_number || 1
        },
        paramsSerializer: params => {
          return qs.stringify(params)
        }
      })
      .then(response => {
        // Enables actions on the form
        this.state.loading = false;

        this.companies = response.data.response.data.data;
        this.pagination = response.data.response.pagination;

        console.log(response.data.response.pagination);
        // this.options = response.data.result;

      })
      .catch(error => {
        // Enables actions on the form again
        this.state.loading = false;
        console.log(error);
      })
    },
    onBenefitSelection(value){
      this.company.benefits = value;
    },
    onTimezoneSelection(value){
      this.filters.timezones = value;
    },
    onRegionSelection(value){
      this.filters.countries = value;
    },
    onToolSelection(value){
      this.filters.tools = value;
    },
    onIndustrySelection(value){
      this.filters.industries = value;
    },
    onRemoteLevelSelection(value){
      this.filters.remote_level = value;
    },
    onSizeSelection(value){
      this.filters.size = value;
    },
    onTypeSelection(value){
      this.company.type = value;
    },
    onDialogClose(){
      this.dialog_company = '';
      var body = document.body;
      body.classList.remove("no-scroll");
      history.replaceState(null, '', '/companies');
    },
    fetchIDs: function(objects){
        var ids = [];
        objects.forEach(selection => ids.push(selection.id));
        return ids;
    },
  },
  components: {
    inputtools, inputtimezones, inputcountries, inputbenefits, inputcompanytype, inputdropdowntags, inputfilter, dialogcompany, pagination
  }
}
</script>
