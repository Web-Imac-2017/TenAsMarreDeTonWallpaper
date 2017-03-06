'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import {handleHttpError, handleRequestError} from '../../../utils/fetch-utils.js';

const ProfileMenu = Vue.extend({
  template,

  computed:{
    bus: function(){ return bus; }
  },

  methods:{
    tryLogout(event){
      let _this = this;

      fetch("/TenAsMarreDeTonWallpaper/api/membre/logout", {
            method: 'get'
          }
        )
        // Handle bad http response
        .then(handleHttpError)
        // Handle Json parse
        .then(function(response){ return response.json(); })
        // Handle request errors
        .then(handleRequestError)
        // Login ok
        .then(function(response){
          _this.bus.logout();
        })
        // Error caught
        .catch(function(error){ alert("Erreur lors de la déconnexion. Veuillez recommencer.");});
    },
  }
});

export default ProfileMenu;
