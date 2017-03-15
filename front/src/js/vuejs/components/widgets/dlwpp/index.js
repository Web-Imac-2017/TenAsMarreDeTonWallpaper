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

    props: {
        'wallpaper': {type: Object, default: function(){ return {}; }}
    },

    data: () => ({
        // Pas 0, sinon le placeholder ne s'affiche pas.
        custom:{l:"", h:""},
        ecran:ecran,
        afficher_fleches:false // Mettre à false si on n'implémente pas prev et next
    }),

    computed: {
        wpp: function() {
            return {
                titre:this.wallpaper.title,
                auteur:this.wallpaper.author,
                genre:this.wallpaper.category,
                url:this.wallpaper.url,
                l:this.wallpaper.width,
                h:this.wallpaper.height
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

                // XXX Un jour faudra peut-être les faire varier celles-là
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
          let v = e.target.valueAsNumber;
          e.target.style.color = "white";
          if(v==NaN || v==0)
              e.target.style.color = "tomato";
          else if(!(min < v && v < max))
              e.target.style.color = "orange";
      },
      on_largeur_custom: function(e) { this.on_dim_custom(e, 99, this.wpp.l); },
      on_hauteur_custom: function(e) { this.on_dim_custom(e, 99, this.wpp.h); },
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
