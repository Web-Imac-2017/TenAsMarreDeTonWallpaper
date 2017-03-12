'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const Slide_wpp = Vue.extend({
  template,

  props: {
    'wallpapers-height-rem': { type: Number, default: function(){ return 6; }}
  },

  data() {
    return {
    	wallpapers:[
			{ thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/sunset.jpg', width: 1920, height: 1080 },
            { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/nyan-cat.jpg', width: 1920, height: 1080 },
            { thumbnail: '/TenAsMarreDeTonWallpaper/media/wallpapers/rain.png', width: 2000, height: 1500 }
        ],
        index: 0
    };
  },

  computed: {
    visible() {
        return this.index === this.parent.index
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
    }
  },


});

export default Slide_wpp;
