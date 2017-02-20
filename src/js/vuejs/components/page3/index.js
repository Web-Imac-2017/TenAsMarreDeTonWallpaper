'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Page2 = Vue.extend({
  template,

  props: {
    list: {
      type    : Array,
      required: true,
    },
  },

  data() {
    return {
      item: {},
    };
  },

  created() {
    for (var i = 0; i < this.list.length; i++) {
      if (this.list[i].id == this.$route.params.item_id) {
        this.item = this.list[i];
      }
    }

    console.log(this.item);
  }
});

export default Page2;
