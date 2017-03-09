'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';

const TestDlWpp = Vue.extend({
  template,

  components: {
      'default-layout': DefaultLayout,
  },

  created(){
    // Add 'Participate' link in header
    bus.headerLinks['home-participate'] = { text: 'Participer', url:'/TenAsMarreDeTonWallpaper/participate' };
  },

  beforeDestroy(){
    // Remove 'Participate' link in header
    delete bus.headerLinks['home-participate'];
  }
});

export default TestDlWpp;
