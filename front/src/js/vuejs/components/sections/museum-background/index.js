'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const MuseumBackground = Vue.extend({
  template,

  props: {
    'wallpapers-height-rem': { type: Number, default: function(){ return 10; }}
  },

  data() {
    return {
        offset: 0, // left offset (negative).
        wallpapers:[
            { thumbnail: '/TenAsMarreDeTonWallpaper/media/sunset.jpg', width: 1920, height: 1080 },
            { thumbnail: '/TenAsMarreDeTonWallpaper/media/nyan-cat.jpg', width: 1920, height: 1080 },
            { thumbnail: '/TenAsMarreDeTonWallpaper/media/rain.png', width: 2000, height: 1500 },
            { thumbnail: '/TenAsMarreDeTonWallpaper/media/the_guardian_of_the_stars.png', width: 2560, height: 1440 }
        ]
    };
  },

  methods: {
    getThumbnailCss(wallpaper){
        let ret = {
            'background-image': 'url('+wallpaper.thumbnail+')',
            'width': (wallpaper.width / wallpaper.height * this.wallpapersHeightRem)+'rem',
            'height': this.wallpapersHeightRem+'rem'
        };
        return ret;
    }
  },

  computed:{
    offsetStyle: function(){ return {
      'margin-left': this.offset + 'rem'
    }; }
  },

  created() {
    this.offset = Math.random() * -8;
  }
});

export default MuseumBackground;
