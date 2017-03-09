'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const RainbowAnswer = Vue.extend({
  template,

  methods: {
    onClick(){ this.$emit('click'); }
  }
});

export default RainbowAnswer;
