'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const DefaultLayout = Vue.extend({
  template,

  props: {
  },

  data() {
    return {

    };
  },

  created() {
      
  }
});

export default DefaultLayout;
