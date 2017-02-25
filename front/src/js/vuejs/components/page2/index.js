'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Page2 = Vue.extend({
  template,

  props: {
    list: {
      type    : Array,
      required: true,
    },
  },
});

export default Page2;
