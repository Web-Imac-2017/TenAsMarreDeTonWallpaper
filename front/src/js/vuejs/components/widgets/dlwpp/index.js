'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const DlWpp = Vue.extend({
  template,

methods: {
  fermer: function() {
    alert("TODO fermer ce widget !");
  }
}
});

export default DlWpp;
