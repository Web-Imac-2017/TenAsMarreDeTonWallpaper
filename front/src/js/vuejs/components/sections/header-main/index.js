'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import RainbowBar from '../../widgets/rainbow-bar/index.js';

const HeaderMain = Vue.extend({
  template,

  components: {
    'rainbow-bar': RainbowBar
  },

  methods:{
    onToggleSidebarButton : function(){
      this.$emit('toggle-sidebar');
    }
  }
});

export default HeaderMain;
