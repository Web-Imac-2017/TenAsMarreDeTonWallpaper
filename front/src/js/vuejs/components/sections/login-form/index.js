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
    msgs: {
      get: function() { return (this.msgerror || this.msgok);},
      set: function(newValue) { this.msgerror = newValue; this.msgok = newValue; }
    },
    bus: function(){ return bus; }
  },

  methods:{
    getLoginFormData() { return new FormData(this.$refs.loginForm); },
    getRegisterFormData() { return new FormData(this.$refs.registerForm);},

    tryLogin(event){
      let _this = this;
      _this.msgs = null;

      fetch("/TenAsMarreDeTonWallpaper/api/membre/login", {
            method: 'post',
            body: _this.getLoginFormData()
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
          if(!('data' in response)) throw Error('Données de connexion manquantes.');
          _this.bus.login(response.data);
        })
        // Error caught
        .catch(function(error){ _this.msgerror = error.message; console.log(error);});
    },

    tryRegister(event){
      let _this = this;
      _this.msgs = null;

      fetch("/TenAsMarreDeTonWallpaper/api/membre/add", {
            method: 'post',
            body: _this.getRegisterFormData()
          }
        )
        // Handle bad http response
        .then(handleHttpError)
        // Handle Json parse
        .then(function(response){ return response.json(); })
        // Handle request errors
        .then(handleRequestError)
        // Register ok
        .then(function(response){ _this.msgok = "Inscription terminée, vous pouvez vous connecter."; _this.$refs.registerForm.reset(); })
        // Error caught
        .catch(function(error){ _this.msgerror = error.message; console.log(error);});
    },
    
  }
});

export default LoginForm;
