'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Slider = Vue.extend({
  template,

  props: {
    'wallpapers-height-rem': { type: Number, default: function(){ return 12; }},
    'wallpapers': {type: Array, default: function(){ return []; }}, // liste des wallpapers sous la forme d'un tableaux d'objets.
  },

  data() {
    return {
    	offset: 0,
        pas: screen.width * 2 / 3,
        openWallPicture: null
    };
  },

  computed: {
      totalwidth: function(){ // in px
        let ret = 0;
        for( let wallpaper of this.wallpapers ){
            ret += (wallpaper.largeur / wallpaper.hauteur * this.wallpapersHeightRem + (40 / 24)) * 24;
        }
        return ret + 40;
      },
      hideLeftArrow: function(){
          return this.offset <= 0;
      },
      hideRightArrow: function(){
          return this.offset >= this.totalwidth - screen.width;
      },
      showOverlay: function(){
          return this.openWallPicture != null;
      }
  },

  methods: {
    getThumbnailCss(wallpaper){
        let ret = {
            'background-image': 'url('+wallpaper.url_thumb+')',
            'width': (wallpaper.largeur / wallpaper.hauteur * this.wallpapersHeightRem)+'rem',
            'height': this.wallpapersHeightRem+'rem'
        };
        return ret;
    },
    onClickArrow(direction){ // 1 ou -1
        this.offset += direction * this.pas;
        if(this.offset > this.totalwidth - screen.width) this.offset = this.totalwidth - screen.width;
        if(this.offset < 0) this.offset = 0;
    },
    openWallpaper(wallpaper){
        this.$emit('select-wallpaper', wallpaper);
    }
  }


});

export default Slider;
