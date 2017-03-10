'use strict';

import Vue from 'vue/dist/vue';
import {isInt} from '../../../utils/isInt';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const DlWpp = Vue.extend({
    template,

    // TODO(yoan): Détecter le rapport largeur:hauteur.
    // TODO(yoan): Aligner les logos de téléchargement sur la gauche.
    // TODO(yoan): Qu'ets-ce qui se passe s'il veut télécharger avec une résolution trop grande ? Et pas assez ?
    data: () => ({
        // Pas 0, sinon le placeholder ne s'affiche pas.
        largeur_custom:"",
        hauteur_custom:"",
        largeur_ecran:window.screen.width,
        hauteur_ecran:window.screen.height
    }),

    methods: {
      fermer: function() { alert("TODO fermer ce widget !"); },
      prev: function() { alert("TODO Wallpaper précédent !"); },
      next: function() { alert("TODO Wallpaper suivant !"); },
      telecharger: function(l, h) {
          if(!(isInt(l) || isInt(h)) || l==0 || h==0) {
              alert("XXX Entrées invalides !");
              return;
          }
          alert("TODO Télécharger wallpaper (" + l + " x " + h + ")");
      },
      telecharger_native: function() {
          this.telecharger(this.$data.largeur_ecran, this.$data.hauteur_ecran);
      },
      telecharger_custom: function() {
          this.telecharger(this.$data.largeur_custom, this.$data.hauteur_custom);
      }
    }
});

export default DlWpp;
