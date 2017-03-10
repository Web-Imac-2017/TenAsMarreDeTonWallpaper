'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import Presentation from '../../sections/presentation/index.js';

const Home = Vue.extend({
  template,

  data(){return{
    headerLinks: {
    },
  };},

  components: {
      'default-layout': DefaultLayout,
      'presentation': Presentation
  },
});

export default Home;
