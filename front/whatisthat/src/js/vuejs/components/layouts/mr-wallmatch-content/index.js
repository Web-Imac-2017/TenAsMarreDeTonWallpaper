'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import MrWallmatch from '../../widgets/mr-wallmatch/index.js';

const MrWallmatchContent = Vue.extend({
  template,

  props: {
  },

  data() {
    return {

    };
  },

  components: {
    'mr-wallmatch': MrWallmatch
  },

  created() {
      
  }
});

export default MrWallmatchContent;
