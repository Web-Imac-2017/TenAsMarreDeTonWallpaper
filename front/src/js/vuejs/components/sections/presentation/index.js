'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';

const Presentation = Vue.extend({
  template,

  data(){ return {
    randomInt: 0
  };},

  components: {
    'mr-wallmatch-content': MrWallmatchContent
  },

  methods:{
    rollWallmatch(){
      this.randomInt = Math.random();
    }
  }
});

export default Presentation;
