'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../bus/index.js';

const App = Vue.component('app', {
  template,

  data(){ return{
    preload: true,            // empêche les animation de s'effectuer au chargement de la page
  };},

  computed:{
    bus: function(){ return bus; }
  },

  mounted: function(){
    // preload empêche les animations CSS de s'effectuer au chargement de la page.
    // Une fois le component monté, on retire preload pour rendre aux éléments leurs animations.
    let varthis = this;
    setTimeout(function(){
        varthis.preload = false;
    },400);
  }
});

export default App;
