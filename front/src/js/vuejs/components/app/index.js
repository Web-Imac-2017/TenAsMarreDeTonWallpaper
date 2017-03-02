'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const App = Vue.component('app', {
  template,

  data(){ return{
    preload: true,            // empêche les animation de s'effectuer au chargement de la page
  };},

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
