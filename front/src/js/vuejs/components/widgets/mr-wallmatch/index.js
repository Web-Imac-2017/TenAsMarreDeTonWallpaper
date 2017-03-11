'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const MrWallmatch = Vue.extend({
  template,

  props:{
    roll: Number
  },

  data() {
    return {
      pictures:[
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_1.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_2.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_3.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_4.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_5.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_6.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_7.png',
        '/TenAsMarreDeTonWallpaper/www/assets/img/mr-wallmatch/mrwm_8.png',
      ],
    };
  },

  computed:{
    wallmatchStyle: function(){return {backgroundImage: 'url("'+ this.pictures[this.current] +'")'}},
    current: function(){return Math.floor((this.roll * this.pictures.length));}
  },

});

export default MrWallmatch;
