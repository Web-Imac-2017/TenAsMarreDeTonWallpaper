'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Header_main = Vue.extend({
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

export default Header_main;

