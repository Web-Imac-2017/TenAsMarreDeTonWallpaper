'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import HeaderMain from '../../sections/header-main/index.js';

const DefaultLayout = Vue.extend({
  template,

  components: {
    'header-main': HeaderMain,
  }
});

export default DefaultLayout;
