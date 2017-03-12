'use strict';

import Vue from 'vue/dist/vue';

const Bus = Vue.extend({

  data(){ return{
    userLoggedIn: null,    // objet contenant les information de l'utilisateur connecté. null si aucun utilisateur connecté.
    //userLoggedIn: {id: 42, pseudo: 'Thaledric', admin: true, moderateur: true, mail: 'thaledric@gmail.com', avatar: '/TenAsMarreDeTonWallpaper/media/avatars/thaledric.png'},
    headerLinks: {},
    altAvatar : "/TenAsMarreDeTonWallpaper/www/assets/icons/user-white.png"
  };},

  computed: {
    isUserLoggedIn: function(){
      return this.userLoggedIn != null;
    },
    avatar : function() {
        if(!this.userLoggedIn)
            return this.altAvatar;
        if(!this.userLoggedIn.avatar)
            return this.altAvatar;
        if(this.userLoggedIn.avatar == "")
            return this.altAvatar;
        return this.userLoggedIn.avatar;
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
