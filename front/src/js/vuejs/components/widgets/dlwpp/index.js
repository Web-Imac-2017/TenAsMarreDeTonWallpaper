'use strict';

import Vue from 'vue/dist/vue';
import {isInt} from '../../../utils/isInt';

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
          let l = this.$data.largeur;
          let h = this.$data.hauteur;
          if(!(isInt(l) || isInt(h)) || l==0 || h==0) {
              alert("XXX Entrées invalides !");
              return false;
          }
          alert("TODO Télécharger wallpaper (" + l + " x " + h + ")");
      }
    }
});

export default DlWpp;
