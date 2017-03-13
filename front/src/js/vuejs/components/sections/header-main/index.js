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

  props:{
    'links': {type: Object, default: function(){ return {}; }}
  },

  computed:{
    bus: function(){ return bus; },
  },

  methods:{
    onToggleSidebarButton : function(){
      this.$emit('toggle-sidebar');
    },
    onClickHeaderLink: function(curlink){
      if('url' in curlink){ router.push(curlink.url); return; }
      if('callback' in curlink){ curlink.callback(); return; }
    }
  }
});

export default HeaderMain;
