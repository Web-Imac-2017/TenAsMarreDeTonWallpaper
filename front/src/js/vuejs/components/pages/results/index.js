'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';
import Slider from '../../widgets/slider/index.js';
import RainbowBar from '../../widgets/rainbow-bar/index.js';
import DlWpp from '../../widgets/dlwpp/index.js';

const Results = Vue.extend({
  template,

  data(){return{
    headerLinks: {
      'results-retry': { text: 'Recommencer', url:{name: 'findYourWallpaper'} },
    },
    randomInt: 0,
    selectedWallpaper: null
  };},

  computed:{
    hasSelectedWallpaper: function() { return this.selectedWallpaper != null; },
    passedWallpaper: function(){ return this.hasSelectedWallpaper ? this.selectedWallpaper : {}; }
  },

  components: {
      'default-layout': DefaultLayout,
      'mr-wallmatch-content': MrWallmatchContent,
      'slider': Slider,
      'rainbow-bar': RainbowBar,
      'dlwpp': DlWpp,
  },

  methods:{
      prevQuestion(){
        let _this = this;
      },
      selectWallpaper(wallpaper){
        this.selectedWallpaper = wallpaper;
      }
  }
});

export default Results;
