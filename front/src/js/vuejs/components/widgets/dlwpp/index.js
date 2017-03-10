'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const DlWpp = Vue.extend({
    template,

    data: () => ({
        // Pas 0, sinon le placeholder ne s'affiche pas.
        largeur:"", hauteur:""
    }),

    methods: {
      fermer: function() { alert("TODO fermer ce widget !"); },
      prev: function() { alert("TODO Wallpaper précédent !"); },
      next: function() { alert("TODO Wallpaper suivant !"); },
      telecharger: function() {
        alert("TODO Télécharger wallpaper (" +
            this.$data.largeur + " x " + this.$data.hauteur + ")"
        );
      }
    }
});

export default DlWpp;
