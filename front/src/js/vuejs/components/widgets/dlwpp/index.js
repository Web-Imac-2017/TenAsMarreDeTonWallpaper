'use strict';

import Vue from 'vue/dist/vue';
import {isInt} from '../../../utils/isInt';
import {rapports, rapport_chercher, rapport_filtrer} from '../../../utils/rapports';

let template = require('./template.html');
template     = eval(`\`${template}\``);

const ecran = {l:window.screen.width, h:window.screen.height};
// Tests
// const ecran = {l:1000, h:1};
// const ecran = {l:1600, h:900};

const DlWpp = Vue.extend({
    template,

    props: ['titre','pseudo','genre','url','l','h'],

    data: () => ({
        // Pas 0, sinon le placeholder ne s'affiche pas.
        custom:{l:"", h:""},
        ecran:ecran
    }),

    computed: {
        wpp: function() {
            return {
                titre:this.titre,
                uploadeur:{pseudo:this.pseudo},
                genre:this.genre,
                url:this.url,
                l:this.l,
                h:this.h
            }
        },
        sections: function() {
            let trouve = rapport_chercher(ecran);
            let format = trouve==null ? {l:0,h:0,res:[]} : rapport_filtrer({
                rapport:trouve,
                wpp:this.wpp, ecran:ecran, limite:4
            });
            return [
                {vu:true, texte:"Votre définition :", classe:"dlwpp-c2", res:[ ecran ]},
                {vu:trouve!=null, texte:"Votre format ("+format.l+":"+format.h+") :", classe:"dlwpp-c3", res: format.res },
                {vu:true, texte:trouve==null?"Autres : ":"Encore d'autres :", classe:"dlwpp-c4", res:[
                    {l:1024,h:768},
                    {l:800,h:600}
                ]}
            ];
        }
    },

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
