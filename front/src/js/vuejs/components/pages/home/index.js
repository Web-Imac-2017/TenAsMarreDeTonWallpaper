'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import Presentation from '../../sections/presentation/index.js';
import Slider_wpp_home from '../../sections/slider-wpp-home/index.js'

const Home = Vue.extend({
  template,

  components: {
      'default-layout': DefaultLayout,
      'presentation': Presentation,
      'slider' : Slider_wpp_home
  },

  created(){
    // Add 'Participate' link in header
    bus.headerLinks['home-participate'] = { text: 'Participer', url:'/TenAsMarreDeTonWallpaper/participate' };
  },

  beforeDestroy(){
    // Remove 'Participate' link in header
    delete bus.headerLinks['home-participate'];
  }
});

export default Home;
