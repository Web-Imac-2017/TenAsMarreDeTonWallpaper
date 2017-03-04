'use strict';

import Vue from 'vue/dist/vue';

const Bus = Vue.extend({

  data(){ return{
    //userLoggedIn: null    // objet contenant les information de l'utilisateur connecté. null si aucun utilisateur connecté.
    userLoggedIn: {id: 42, pseudo: 'Thaledric', admin: true, moderateur: true, mail: 'thaledric@gmail.com', avatar: '/TenAsMarreDeTonWallpaper/media/avatars/thaledric.png'},
    headerLinks: {}
  };},

  computed: {
    isUserLoggedIn: function(){
      return this.userLoggedIn != null;
    }
  },

  methods: {
      logout: function(){
          this.userLoggedIn = null;
      },
      login: function(response){
        this.userLoggedIn = response;
      }
  }
});

var bus = new Bus();

export default bus;
