'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import DefaultLayout from '../../layouts/default-layout/index.js';
import Presentation from '../../sections/presentation/index.js';

const Home = Vue.extend({
  template,

  components: {
      'default-layout': DefaultLayout,
      'presentation': Presentation
  }
});

export default Home;
