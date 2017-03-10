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
    sidebarState: 0 /* 0 = Hidden, 1 = Shown, 2 = Closed (hidden after shown) */
  };},

  props:{
    'headerLinks': {type: Object, default: function(){ return {}; }}
  },

  computed:{
    bus: function(){ return bus; },
    sidebarHidden: function() { return this.sidebarState == 0; },
    sidebarOpen: function() { return this.sidebarState == 1; },
    sidebarClosed: function() { return this.sidebarState == 2; }
  },

  components: {
    'header-main': HeaderMain,
    'login-form': LoginForm,
    'profile-menu': ProfileMenu,
  },

  methods: {
    toggleSidebar: function(){
      this.sidebarOpen ? this.closeSidebar() : this.openSidebar();
    },
    closeSidebar: function(){
      if(!this.sidebarHidden) this.sidebarState = 2;
    },
    openSidebar: function(){
      this.sidebarState = 1;
    }
  }
});

export default DefaultLayout;
