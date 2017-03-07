'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Slider_wpp_home = Vue.extend({
  template,

  data() {
    return {
    };
  },


});

export default Slider_wpp_home;