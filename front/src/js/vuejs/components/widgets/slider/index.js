'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Slider = Vue.extend({
  template,

  props: {
    'wallpapers-height-rem': { type: Number, default: function(){ return 12; }},
    //'wallpapers': {type: Array, default: function(){ return []; }}, // liste des wallpapers sous la forme d'un tableaux d'objets.
    'wallpapers': {type: Array, default: function(){ return [
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/sunset.jpg', width: 1920, height: 1080, title: "Sunset", author: "Jeandas la menace"},
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/nyan-cat.jpg', width: 1920, height: 1080, title: "Rainbow Nyan Cat", author: "Houguelluque" },
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/rain.png', width: 2000, height: 1500, title: "Rain", author: "Jean-Guillain" },
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/the_guardian_of_the_stars.png', width: 2560, height: 1440, title: "The guardian of the stars", author: "Marcelle la pucelle" },
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/sunset.jpg', width: 1920, height: 1080, title: "Sunset", author: "Jeandas la menace"},
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/nyan-cat.jpg', width: 1920, height: 1080, title: "Rainbow Nyan Cat", author: "Houguelluque" },
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/rain.png', width: 2000, height: 1500, title: "Rain", author: "Jean-Guillain" },
        { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/the_guardian_of_the_stars.png', width: 2560, height: 1440, title: "The guardian of the stars", author: "Marcelle la pucelle" }
    ]; }}
  },

  data() {
    return {
    	offset: 0,
        pas: screen.width * 2 / 3
    };
  },

  computed: {
      totalwidth: function(){ // in px
        let ret = 0;
        for( let wallpaper of this.wallpapers ){
            ret += (wallpaper.width / wallpaper.height * this.wallpapersHeightRem + (40 / 24)) * 24;
        }
        return ret + 40;
      },
      hideLeftArrow: function(){
          return this.offset <= 0;
      },
      hideRightArrow: function(){
          return this.offset >= this.totalwidth - screen.width;
      }
  },

  methods: {
    getThumbnailCss(wallpaper){
        let ret = {
            'background-image': 'url('+wallpaper.thumbnail+')',
            'width': (wallpaper.width / wallpaper.height * this.wallpapersHeightRem)+'rem',
            'height': this.wallpapersHeightRem+'rem'
        };
        return ret;
    },
    onClickArrow(direction){ // 1 ou -1
        this.offset += direction * this.pas;
        if(this.offset > this.totalwidth - screen.width) this.offset = this.totalwidth - screen.width;
        if(this.offset < 0) this.offset = 0;
    }
  }


});

export default Slider;
