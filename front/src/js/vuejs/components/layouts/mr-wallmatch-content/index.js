'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import MrWallmatch from '../../widgets/mr-wallmatch/index.js';
import MuseumBackground from '../../sections/museum-background/index.js';

const MrWallmatchContent = Vue.extend({
  template,

  props: {
    'showBackground': {type: Boolean, default: function(){ return true; }},
    'roll' : {type: Number}
  },

  data() {
    return {

    };
  },

  components: {
    'mr-wallmatch': MrWallmatch,
    'museum-background': MuseumBackground
  },

  created() {
      
  }
});

export default MrWallmatchContent;
