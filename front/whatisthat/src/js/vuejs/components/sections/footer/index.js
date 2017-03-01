'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Footer_main = Vue.extend({
  template,

  props: {
  },

  data() {
    //active: 'home',
    return {
    };
  },

  methods: {
  	makeActive: function(item){
    	this.active = item;
    }
  },

  created() {
      
  }
});

export default Footer_main;
