'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../bus/index.js';

const App = Vue.component('app', {
  template,
  
  computed:{
    bus: function(){ return bus; }
  },
});

export default App;
