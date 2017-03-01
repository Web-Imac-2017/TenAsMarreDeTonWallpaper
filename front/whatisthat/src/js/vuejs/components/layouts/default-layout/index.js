'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

//import Header from '../../sections/header/index.js';
import Footer_main from '../../sections/footer/index.js';

const DefaultLayout = Vue.extend({
  template,

  props: {
  },

  data() {
    return {

    };
  },

  components: {
    //'header': Header,
    'footer-main': Footer_main
  },

  created() {
      
  }
});

export default DefaultLayout;
