'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Page1 = Vue.extend({
  template,
});

export default Page1;
