'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import HeaderMain from '../../sections/header-main/index.js';
import LoginForm from '../../sections/login-form/index.js';
import ProfileMenu from '../../sections/profile-menu/index.js';

const DefaultLayout = Vue.extend({
  template,

  data(){ return{
    sidebarOpen: false
  };},

  computed:{
    bus: function(){ return bus; }
  },

  components: {
    'header-main': HeaderMain,
    'login-form': LoginForm,
    'profile-menu': ProfileMenu,
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
