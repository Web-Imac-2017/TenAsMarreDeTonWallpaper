'use strict';

import Vue from 'vue/dist/vue';
import {isInt} from '../../../utils/isInt';
import {rapports} from '../../../utils/rapports';

let template = require('./template.html');
template     = eval(`\`${template}\``);

console.log(rapports);

const DlWpp = Vue.extend({
    template,

    // TODO(yoan): Détecter le rapport largeur:hauteur.
    data: () => ({
        // XXX devraient être des props
        wpp: {
            titre:"Pégase",
            uploadeur:{pseudo:"Jeandas la Menace"},
            genre:"Peinture numérique",
            url:"/TenAsMarreDeTonWallpaper/media/wallpapers/the_guardian_of_the_stars.png",
            l:2560,
            h:1540
        },
        // Pas 0, sinon le placeholder ne s'affiche pas.
        custom:{l:"", h:""},
        ecran:{l:window.screen.width, h:window.screen.height},
        sections: [
            {texte:"Votre définition :", classe:"dlwpp-c2", res:[
                {l:window.screen.width,h:window.screen.height}
            ]},
            {texte:"Votre format (16:9) :", classe:"dlwpp-c3", res:[
                {l:3840,h:2160},
                {l:1600,h:900},
                {l:1366,h:768},
                {l:1280,h:720}
            ]},
            {texte:"Encore d'autres :", classe:"dlwpp-c4", res:[
                {l:1024,h:768},
                {l:800,h:600}
            ]}
        ]
    }),

    methods: {
      fermer: function() { alert("TODO fermer ce widget !"); },
      prev: function() { alert("TODO Wallpaper précédent !"); },
      next: function() { alert("TODO Wallpaper suivant !"); },
      on_dim_custom: function(e, min, max) {
          let v = e.target.value;
          e.target.style.outline = "green solid 2px";
          if(v == "")
              e.target.style.outlineWidth = "0px";
          else if(!isInt(v) || v==0)
              e.target.style.outlineColor = "red";
          else if(!(min < v && v < max))
              e.target.style.outlineColor = "orange";
      },
      on_largeur_custom: function(e) { this.on_dim_custom(e, 500, this.$data.wpp.l); },
      on_hauteur_custom: function(e) { this.on_dim_custom(e, 500, this.$data.wpp.h); },
      telecharger: function(l, h) {
          if(!(isInt(l) || isInt(h)) || l==0 || h==0) {
              alert("Veuillez entrer des dimensions valides.");
              return;
          }
          alert("TODO Télécharger wallpaper (" + l + " x " + h + ")");
      },
      telecharger_native: function() {
          this.telecharger(this.$data.ecran.l, this.$data.ecran.h);
      },
      telecharger_custom: function() {
          this.telecharger(this.$data.custom.l, this.$data.custom.h);
      }
    }
});

export default DlWpp;
