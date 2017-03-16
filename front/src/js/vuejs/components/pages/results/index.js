'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import router from '../../../router/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';
import Slider from '../../widgets/slider/index.js';
import RainbowBar from '../../widgets/rainbow-bar/index.js';
import DlWpp from '../../widgets/dlwpp/index.js';
import {handleHttpError, handleRequestError} from '../../../utils/fetch-utils.js';

const Results = Vue.extend({
  template,

  data(){return{
    headerLinks: {
      'results-retry': { text: 'Recommencer', url:{name: 'findYourWallpaper'} },
    },
    randomInt: 0,
    selectedWallpaper: null,
  };},

  computed:{
    hasSelectedWallpaper: function() { return this.selectedWallpaper != null; },
    passedWallpaper: function(){ return this.hasSelectedWallpaper ? this.selectedWallpaper : {}; },
    wallpapers: function(){ return bus.results; }
  },

  components: {
      'default-layout': DefaultLayout,
      'mr-wallmatch-content': MrWallmatchContent,
      'slider': Slider,
      'rainbow-bar': RainbowBar,
      'dlwpp': DlWpp,
  },

  methods:{
      prevQuestion(){
        let _this = this;
      },
      selectWallpaper(wallpaper){
        this.selectedWallpaper = wallpaper;
      },
      onCloseDlwpp(){
        this.selectedWallpaper = null;
      },
      prevQuestion(){
        let _this = this;
        if(_this.isRaised) return false;

          fetch("/TenAsMarreDeTonWallpaper/api/algo/undo", {
              method: 'get',
              credentials: 'include'
            }
          )
          // Handle bad http response
          .then(handleHttpError)
          // Handle Json parse
          .then(function(response){ return response.json(); })
          // Handle request errors
          .then(handleRequestError)
          // Next Question ok
          .then(function(response){
            if(!('data' in response)) throw Error('Données de question manquantes.');
            router.push({name: 'findYourWallpaper'}); return;
          })
          // Error caught
          .catch(function(error){ alert(error.message); console.log(error.message);});
      }
  }
});

export default Results;
