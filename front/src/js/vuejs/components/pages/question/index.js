'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import DefaultLayout from '../../layouts/default-layout/index.js';
import RainbowAnswer from '../../widgets/rainbow-answer/index.js';
import MrWallmatchContent from '../../layouts/mr-wallmatch-content/index.js';
import {handleHttpError, handleRequestError} from '../../../utils/fetch-utils.js';

const QuestionPage = Vue.extend({
  template,

  data(){return{
    /*question:{
      text: 'Votre wallpaper représenterait-il un paysage urbain',
      quote: '« Il faut des monuments aux cités de l’Homme, autrement où serait la différence entre la ville et la fourmilière ? »',
      quoteAuthor: '— Victor Hugo',
      number: 1,
      answerCategories: ['Une photo', 'Une fractale', 'Ta mère']
    },*/
    //question: null,
    isRaised: true,
    selectedAnswer: 0, /* 1 - 5, 0 si inconnu */
    answersStyles: [],
  };},

  computed:{
    isFirstQuestion(){ return this.question.number == 1; },
    answersList(){ return this.hasQuestion
                      ? this.isFirstQuestion 
                          ? [this.question.answerCategories[0], this.question.answerCategories[1], this.question.answerCategories[2], 'Rien de tout ça', 'Surprenez-moi !']
                          : ['Non', 'Pas vraiment', 'Peu importe', 'Éventuellement', 'Oui']
                      : ['', '', '', '', ''];
                 },
    hasQuestion(){ return this.question != null; }
  },

  components: {
      'default-layout': DefaultLayout,
      'mr-wallmatch-content': MrWallmatchContent,
      'rainbow-answer': RainbowAnswer,
  },

  methods:{
    answerQuestion(id){ // réponds à la question à partir de la réponse clickée.
      let _this = this;
      if(_this.isRaised) return false;
      _this.riseUpAnswers(id);

      fetch("/TenAsMarreDeTonWallpaper/api/question/next/"+id, {
            method: 'get',
          }
        )
        .then(() => new Promise(resolve => setTimeout(resolve, 1000)))
        // Handle bad http response
        .then(handleHttpError)
        // Handle Json parse
        .then(function(response){ return response.json(); })
        // Handle request errors
        .then(handleRequestError)
        // Next Question ok
        .then(function(response){
          if(!('question' in response)) throw Error('Données de question manquantes.');
          _this.setQuestion(response.question);
        })
        .then(function(){ _this.riseDownAnswers(id); })
        // Error caught
        .catch(function(error){ alert(error.message); console.log(error.message); _this.riseDownAnswers(id)});

    },
    riseUpAnswers(id){
        for(let i = 0 ; i<5 ; ++i){
            this.answersStyles[i] = {transitionDelay: (0.1 * Math.abs(i - id))+'s'};
        }

        this.isRaised = true;
    },
    riseDownAnswers(id){
        /*for(let i = 0 ; i<5 ; ++i){
            this.answersStyles[i] = {transitionDelay: (0.1 * Math.abs(i - id))+'s'};
        }*/ // these are already computed

        this.isRaised = false;
    },
    getQuestion(){
        let _this = this;

        fetch("/TenAsMarreDeTonWallpaper/api/question/current/", {
              method: 'get',
            }
          )
          .then(() => new Promise(resolve => setTimeout(resolve, 1000)))
          // Handle bad http response
          .then(handleHttpError)
          // Handle Json parse
          .then(function(response){ return response.json(); })
          // Handle request errors
          .then(handleRequestError)
          // Current Question ok
          .then(function(response){
            if(!('question' in response)) throw Error('Données de question manquantes.');
            _this.setQuestion(response.question);
          })
          .then(function(){ _this.riseDownAnswers(id); })
          // Error caught
          .catch(function(error){ alert(error.message); console.log(error.message);});
    },
    prevQuestion(){
        let _this = this;
        if(_this.isRaised) return false;
        _this.riseUpAnswers(id);

        fetch("/TenAsMarreDeTonWallpaper/api/question/prev/", {
              method: 'get',
            }
          )
          .then(() => new Promise(resolve => setTimeout(resolve, 1000)))
          // Handle bad http response
          .then(handleHttpError)
          // Handle Json parse
          .then(function(response){ return response.json(); })
          // Handle request errors
          .then(handleRequestError)
          // Next Question ok
          .then(function(response){
            if(!('question' in response)) throw Error('Données de question manquantes.');
            _this.setQuestion(response.question);
          })
          .then(function(){ _this.riseDownAnswers(id); })
          // Error caught
          .catch(function(error){ alert(error.message); console.log(error.message); _this.riseDownAnswers(id)});
    }
  },

  created(){
    // Add 'Participate' link in header
    bus.headerLinks['question-participate'] = { text: 'Participer', url:'/TenAsMarreDeTonWallpaper/participate' };
    bus.headerLinks['question-abandon'] = { text: 'Abandonner', url:'/TenAsMarreDeTonWallpaper/' };
  },

  mounted(){
    this.getQuestion();
  },

  beforeDestroy(){
    // Remove 'Participate' link in header
    delete bus.headerLinks['question-participate'];
    delete bus.headerLinks['question-abandon'];
  }
});

export default QuestionPage;
