<template>
  <form @submit.prevent="submit()" enctype="multipart/form-data" class="form form--edit form--company-edit">
    <div class="form__header">
        <uploader :company_data.id="company_data.id" @upload="addImage" @remove="removeImage" :uploaded_image="company_data.logo"></uploader>   
        <div class="form__header__info">
          <div class="fieldset fieldset--row fieldset--no-margin">
            <div class="field">
              <label :for="'name_'+company.id" class="label label--required">Company Name</label>
              <input class="input" v-model="company.name" placeholder="Enter your company's name" name="name" :id="'name_'+company.id" type="text">
            </div>
            <div class="field">
              <label :for="'website_'+company.id" class="label label--required">Company Website</label>
              <input class="input" v-model="company.url" name="website" placeholder="" :id="'website_'+company.id" type="url">
            </div>
          </div>
          <div class="field field--full-width">
            <label :for="'about_'+company.id" class="label">What does the company do?</label>
            <textarea class="input" placeholder="Shortly describe what the company does" name="about" :id="'about_'+company.id" cols="30" rows="3" v-model="company.description_short">{{ company.description_short }}</textarea>
          </div>
        </div>
    </div>
    <div class="form__body">
        <section class="form__section">
            <div class="fieldset fieldset--row">
                <div class="field">
                  <label :for="'headquaters'+company_data.id" class="label">Headquaters</label>
                  <inputlocation @selection="onHeadquaterSelection" :company_id="company_data.id" :selected="company_data.headquaters"></inputlocation>
                </div>
                <div class="field">
                  <label for="founded" class="label">Founding years</label>
                  <input  type="number"  v-model="company.founding_years" name="founded" id="founded" maxlength="4" placeholder=".i.e. 2010" class="input">
                </div>
            </div>
        </section>
        <section class="form__section">
            <div class="fieldset fieldset--row">
                <inputcompanytype @selection="onRemoteLevelSelection" :company_id="company_data.id" :title="'Remote Level'" :selected="company_data.remote_level" :options="options.remote_level"></inputcompanytype>
                <inputcompanytype @selection="onSizeSelection" :company_id="company_data.id" :title="'Company Size'" :selected="company_data.size" :options="options.size"></inputcompanytype>
                <inputcompanytype @selection="onTypeSelection" :selected="company_data.type" :company_id="company_data.id" :title="'Company Type'" :options="options.type"></inputcompanytype>
            </div>
        </section>
        <section class="form__section">
            <inputdropdowntags @selection="onIndustrySelection" :selected="company_data.industries" :options="options.industries" :company_data.id="company_data.id"></inputdropdowntags>
        </section>
        <section class="form__section">
            <div class="fieldset fieldset--row">
                <div class="field">
                  <label :for="'twitter_'+company_data.id" class="label">Twitter</label>
                  <input  type="text" name="twitter" :id="'twitter_'+company_data.id" v-model="company.twitter" placeholder="Enter Twitter profile url" autocomplete="off" class="input">
                </div>
                <div class="field">
                  <label :for="'github_'+company_data.id" class="label">Github</label>
                  <input  type="text" name="github" v-model="company.github" :id="'github_'+company_data.id" placeholder="Enter Github profile url" class="input">
                </div>
                <div class="field">
                  <label :for="'facebook_'+company_data.id" class="label">Facebook</label>
                  <input  type="text" name="facebook" v-model="company.facebook" :id="'facebook_'+company_data.id" placeholder="Enter Facebook profile url" class="input">
                </div>
            </div>
        </section>
        <section class="form__section">
            <h2 class="form__section__title">Benefits</h2>
            <div class="scraped_suggestion">
                   <span v-for="benefit in company_benefits" class="scraped_suggestion__item">
                     <b>{{benefit.category}}: </b>{{benefit.name}}
                   </span>
            </div>
            <inputbenefits :options="options.benefits" @selection="onBenefitSelection" :selected="company_data.benefits" :company_id="company_data.id"></inputbenefits>
        </section>
        <section class="form__section">
            <h2 class="form__section__title">Hiring Regions</h2>
            <div class="editor-hiring">
                <inputtimezones :selected="company_data.hiring_regions" :options="options.hiring_regions.timezones" @selection="onTimezoneSelection" :company_id="company_data.id"></inputtimezones>
                <inputcountries :selected="company_data.hiring_regions" :options="options.hiring_regions.regions" @selection="onRegionSelection" :company_id="company_data.id"></inputcountries>
            </div>
        </section>
        <section class="form__section">
            <h2 class="form__section__title">Tech Stack</h2>
            <inputtools @selection="onToolSelection" :company_tools="company_data.tools"></inputtools>
        </section>
    </div>
     <div class="form__footer">
       <button type="submit" :disabled="state.loading" class="btn btn--primary btn--large">
         <span class="btn__text">Save Changes</span>
       </button>
     </div>
  </form>
</template>

<script>
import axios from 'axios';
import {APP_CONFIG} from '../config.js';
import uploader from '../components/UploaderPhoto';
import inputtools from '../components/InputTools';
import inputcountries from '../components/InputCountries';
import inputtimezones from '../components/InputTimezones';
import inputbenefits from '../components/InputBenefits';
import inputcompanytype from '../components/InputCompanyType';
import inputdropdowntags from '../components/InputDropdownTags';
import inputlocation from '../components/InputLocation';
export default {
  name: 'formcompany',
  data () {
    return {
      company: {
        id: this.company_data.id,
        name: this.company_data.name,
        logo: this.company_data.logo,
        url: this.company_data.url,
        description_short: this.company_data.description_short,
        founding_years: this.company_data.founding_years,
        headquaters: this.company_data.headquaters,
        timezones: this.company_data.timezones,
        regions: '',
        tools: this.company_data.tools,
        benefits: '',
        industries: '',
        remote_level: '',
        size: '',
        type: '',
        twitter: this.company_data.twitter,
        facebook: this.company_data.facebook,
        github: this.company_data.github,
      },
      state: {
        loading: false,
      }
    }
  },
  computed: {
  },
  mounted() {
    console.log(this.company_data)
  },
  props: {
    company_data: Object,
    company_benefits: Array,
    options: Object,
  },
  methods: {
    submit(){
      let form_data = new FormData();

      form_data.append('id',this.company.id);
      form_data.append('name',this.company.name);
      form_data.append('description_short',this.company.description_short);
      form_data.append('url',this.company.url);
      form_data.append('logo',this.company.logo);
      form_data.append('founding_years',this.company.founding_years);
      form_data.append('headquaters',this.company.headquaters);
      for (var i = 0; i < this.company.timezones.length; i++) {
          form_data.append('hiring_regions[]', this.company.timezones[i].id);
      }
      for (var i = 0; i < this.company.regions.length; i++) {
          form_data.append('hiring_regions[]', this.company.regions[i].id);
      }
      for (var i = 0; i < this.company.tools.length; i++) {
          form_data.append('tools[]', this.company.tools[i].id);
      }
      for (var i = 0; i < this.company.benefits.length; i++) {
          form_data.append('benefits[]', this.company.benefits[i].id);
      }
      for (var i = 0; i < this.company.industries.length; i++) {
          form_data.append('industries[]', this.company.industries[i].id);
      }
      form_data.append('remote_level',this.company.remote_level);
      form_data.append('size',this.company.size);
      form_data.append('type',this.company.type);
      form_data.append('twitter',this.company.twitter);
      form_data.append('facebook',this.company.facebook);
      form_data.append('github',this.company.github);

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
    },
    addImage(file) {
      this.company.logo = '';
      this.company.logo = file;
    },
    removeImage() {
      this.company.logo = '';
    },
    onBenefitSelection(value){
      this.company.benefits = value;
    },
    onTimezoneSelection(value){
      this.company.timezones = value;
    },
    onRegionSelection(value){
      this.company.regions = value;
    },
    onToolSelection(value){
      this.company.tools = value;
    },
    onHeadquaterSelection(value){
      this.company.headquaters = value;
    },
    onIndustrySelection(value){
      this.company.industries = value;
    },
    onRemoteLevelSelection(value){
      this.company.remote_level = value;
    },
    onSizeSelection(value){
      this.company.size = value;
    },
    onTypeSelection(value){
      this.company.type = value;
    },
    fetchIDs: function(objects){
        var ids = [];
        objects.forEach(selection => ids.push(selection.id));
        return ids;
    },
  },
  components: {
    inputtools, inputtimezones, inputcountries, inputbenefits, inputcompanytype, inputdropdowntags, inputlocation, uploader
  }
}
</script>
