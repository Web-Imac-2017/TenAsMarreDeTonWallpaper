'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import RainbowBar from '../../widgets/rainbow-bar/index.js';

const HeaderMain = Vue.extend({
  template,

  components: {
    'rainbow-bar': RainbowBar
  },

  computed:{
    bus: function(){ return bus; },
    links: function(){ return bus.headerLinks; }
  },

  methods:{
    onToggleSidebarButton : function(){
      this.$emit('toggle-sidebar');
    }
  }
});

export default HeaderMain;
