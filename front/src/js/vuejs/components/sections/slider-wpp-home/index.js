'use strict';

import Vue from 'vue/dist/vue';
import Slide from '../slide-wpp/index.js';

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
    	slides: [],
      showPrev: false,
      showNext: true,
      direction: null
    };
  },

  computed: {
  	slidesCount() { return this.slides.length }
  },

  mounted () {
  	this.slides = this.$children
  	this.slides.forEach((slide,i) => {
  		slide.index = i
  	})
  },

  methods: {
    next() {
    	this.index++
      this.direction = 'next'
    	if (this.index >= this.slidesCount - 1) { this.showNext = false }
      this.showPrev = true
    },
    prev() {
    	this.index--
      this.direction = 'prev'
    	if (this.index === 0 ) { this.showPrev = false}
      this.showNext = true
    }
  }


});

export default Slider_wpp_home;
