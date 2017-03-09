'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import RainbowAnswer from '../../widgets/rainbow-answer/index.js';
import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';

const QuestionPage = Vue.extend({
  template,

  data(){return{
    question:{
      text: 'Votre wallpaper représenterait-il un paysage urbain',
      quote: '« Il faut des monuments aux cités de l’Homme, autrement où serait la différence entre la ville et la fourmilière ? »',
      quoteAuthor: '— Victor Hugo',
      number: 4,
      answerCategories: []
    }
  };},

  computed:{
    isFirstQuestion(){ return this.question.number == 1; }
  },

  components: {
      'default-layout': DefaultLayout,
      'mr-wallmatch-content': MrWallmatchContent,
      'rainbow-answer': RainbowAnswer,
  },

  created(){
    // Add 'Participate' link in header
    bus.headerLinks['question-participate'] = { text: 'Participer', url:'/TenAsMarreDeTonWallpaper/participate' };
    bus.headerLinks['question-abandon'] = { text: 'Abandonner', url:'/TenAsMarreDeTonWallpaper/' };
  },

  beforeDestroy(){
    // Remove 'Participate' link in header
    delete bus.headerLinks['question-participate'];
    delete bus.headerLinks['question-abandon'];
  }
});

export default QuestionPage;
