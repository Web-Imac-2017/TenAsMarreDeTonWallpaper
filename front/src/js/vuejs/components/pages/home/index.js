'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

let DefaultLayout = require('../../layouts/default-layout/index.js');
let Presentation = require('../../sections/presentation/index.js');

const Home = Vue.extend({
  template,

  props: {
  },

  data() {
    return {

    };
  },

  components: {
      'default-layout': DefaultLayout,
      'presentation': Presentation
  },

  created() {

  }
});

export default Home;
