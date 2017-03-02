'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';

const ProfileMenu = Vue.extend({
  template,

  computed:{
    bus: function(){ return bus; }
  }
});

export default ProfileMenu;
