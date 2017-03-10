'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';

const Results = Vue.extend({
  template,

  data(){return{
    headerLinks: {
      'results-participate': { text: 'Participer', url:'/TenAsMarreDeTonWallpaper/participate' },
      'results-retry': { text: 'Recommencer', url:'/TenAsMarreDeTonWallpaper/find' },
    },
    randomInt: 0
  };},

  components: {
      'default-layout': DefaultLayout,
      'mr-wallmatch-content': MrWallmatchContent,
  },

  methods:{
      prevQuestion(){
        let _this = this;
    },
  }
});

export default Results;