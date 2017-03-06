'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';

const Presentation = Vue.extend({
  template,

  props: {
  },

  data() {
    return {

    };
  },

  components: {
    'mr-wallmatch-content': MrWallmatchContent
  },

  created() {

  }
});

export default Presentation;
