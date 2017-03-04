'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import {handleHttpError, handleRequestError} from '../../../utils/fetch-utils.js';

const LoginForm = Vue.extend({
  template,

  data(){return {
    msgerror: null,
    msgok: null
  };},

  computed: {
    loginFormData: function() { return new FormData(this.$refs.loginForm); },
    registerFormData: function() { return new FormData(this.$refs.registerForm); },
    msgs: {
      get: function() { return this.msgerror + ' ' + this.msgok; },
      set: function(newValue) { this.msgerror = newValue; this.msgok = newValue; }
    },
    bus: function(){ return bus; }
  },

  methods:{
    tryLogin(event){
      let _this = this;
      _this.msgs = null;
      console.log(_this.loginFormData);

      fetch("/TenAsMarreDeTonWallpaper/api/membre/login", {
            method: 'post',
            body: _this.loginFormData
          }
        )
        // Handle bad http response
        .then(handleHttpError)
        .catch(function(){ _this.msgerror = "Erreur serveur lors de la connexion."; })
        // Handle Json parse
        .then(function(response){ return response.json(); })
        .catch(function(){ _this.msgerror = "Erreur des données reçues lors de la connexion."; })
        // Handle request errors
        .then(handleHttpError)
        .catch(function(error){ _this.msgerror = error.message; })
        // Login ok
        .then(function(response){ _this.bus.login(response); });
    },

    tryRegister(event){
      let _this = this;
      _this.msgs = null;
      console.log(_this.registerFormData);

      fetch("/TenAsMarreDeTonWallpaper/api/membre/add", {
            method: 'post',
            body: _this.registerFormData
          }
        )
        // Handle bad http response
        .then(handleHttpError)
        .catch(function(){ _this.msgerror = "Erreur serveur lors de l'inscription."; })
        // Handle Json parse
        .then(function(response){ return response.json(); })
        .catch(function(){ _this.msgerror = "Erreur des données reçues lors de l'inscription."; })
        // Handle request errors
        .then(handleHttpError)
        .catch(function(error){ _this.msgerror = error.message; })
        // Login ok
        .then(function(response){ _this.msgok = "Inscription terminée. Vous pouvez vous connecter."; });
    },
    
  }
});

export default LoginForm;
