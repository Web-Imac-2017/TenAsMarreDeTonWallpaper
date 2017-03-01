'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import Home from '../pages/home';

const App = Vue.extend({
  template,
  components: {
      'home': Home
  },
});

export default App;
