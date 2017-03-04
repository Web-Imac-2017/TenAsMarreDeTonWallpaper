'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import {handleHttpError} from '../../../utils/fetch-utils.js';

const LoginForm = Vue.extend({
  template,

  data(){return {
    msg: null
  };},

  methods:{
    tryLogin(event){
      let _this = this;
      _this.msg = null;
      fetch(
        "/TenAsMarreDeTonWallpaper/api/login", {
            method: 'post',
            body: new FormData(this.$refs.loginForm)
          }
        ).then(handleHttpError)
        .catch(function(){ _this.msg = "Erreur serveur lors de la connexion."; })
        .then(function(response){ return response.json(); })
        .then(function(json){
          if(_this.isError(json)) throw new Error('Identifiant et/ou mot de passe incorrect(s).')
          else bus.login(json);
        })
        .catch(function(error){ _this.msg = error; })
    },

    tryRegister(event){
      alert("try register");
    },

    isError(json){
      if(!json || 'error' in json) return json.error; return false;
    }
  }
});

export default LoginForm;
