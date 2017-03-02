'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import HeaderMain from '../../sections/header-main/index.js';
import LoginForm from '../../sections/login-form/index.js';

const DefaultLayout = Vue.extend({
  template,

  data(){ return{
    sidebarOpen: false
  };},

  components: {
    'header-main': HeaderMain,
    'login-form': LoginForm,
  },

  methods: {
    toggleSidebar: function(){
      this.sidebarOpen = !this.sidebarOpen;
    },
    hideSidebar: function(){
      this.sidebarOpen = false;
    }
  }
});

export default DefaultLayout;
