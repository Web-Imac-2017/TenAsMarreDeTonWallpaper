'use strict';

import Vue from 'vue/dist/vue';
import Slide from '../slide_wpp/index.js';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Slider_wpp_home = Vue.extend({
  template,

  components: {
        "slide": Slide
    },

  props: {
    'wallpapers-height-rem': { type: Number, default: function(){ return 6; }}
  },

  data() {
    return {
    	index: 0,
    	slides: []
    };
  },

  computed: {
  	slidesCount() { return this.slides.length }
  },

  mounted () {
  	this.slides = this.children
  	this.slides.forEach((slide,i) => {
  		slide.index = i
  	})
  },

  methods: {
    next() {
    	this.index++
    	if (this.index >= this.slidesCount) { this.index = 0 }
    },
    prev() {
    	this.index--
    	if (this.index < 0 ) { this.index = this.slidesCount - 1 }
    }
  }


});

export default Slider_wpp_home;
