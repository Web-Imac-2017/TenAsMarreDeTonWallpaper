'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const question = Vue.extend({
  template,
  el: '#MEL_wpp',
  data: {
    newWallpaper: '',
    wpps: [
      '',
      '',
      ''
    ]
  },
  methods: {
    addNewWallpaper: function () {
      this.wpps.push(this.newWallpaper)
      this.newWallpaper = ''
    }
  },
});

Vue.component('mel_wallpaper', {
  template: '\
    <li>\
      {{ title }} </br> {{ author }} </br> {{ type }}\
      <button v-on:click="$emit(\'zoom\')"> O </button>\
      <button v-on:click="$emit(\'validate\')"> V </button>\
      <button v-on:click="$emit(\'remove\')"> X </button>\
    </li>\
  ',
  props: ['title','author','type']
})


export default question;

