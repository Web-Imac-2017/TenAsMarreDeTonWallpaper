'use strict';

import Vue from 'vue/dist/vue';
import {handleHttpError, handleRequestError} from '../../utils/fetch-utils.js';


const Bus = Vue.extend({

    data() {
        return {
            //userLoggedIn: {id: 42, pseudo: 'Thaledric', admin: true, moderateur: true, mail: 'thaledric@gmail.com', avatar: '/TenAsMarreDeTonWallpaper/media/avatars/thaledric.png'},
            userLoggedIn: null,
            headerLinks: {},
            altAvatar : "/TenAsMarreDeTonWallpaper/www/assets/icons/user-white.png",
            results: []
        };
    },

    created: function() {
        let _this = this;
        fetch("/TenAsMarreDeTonWallpaper/api/membre/getInfo", {
            method: 'post',
            credentials: 'include',
            cache:'no-cache'
            //body: _this.getLoginFormData()
        })
        .then(handleHttpError)
        .then(function(response){ return response.json(); })
        .then(handleRequestError)
        .then(function(response){
            _this.userLoggedIn = response.data;
            //console.log("fetched : " + JSON.stringify(_this.userLoggedIn));
        })
        .catch(function(error){ console.log("Erreur : " + error.message); });
    },

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
