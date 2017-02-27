'use strict';

import Vue from 'vue/dist/vue';
import Slider_wpp_home from '../slider_wpp_home';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const App = Vue.extend({
  template,

  data() {
    return {
    };
  },

  components: {
  	'slider': Slider_wpp_home
  },

});

export default App;
