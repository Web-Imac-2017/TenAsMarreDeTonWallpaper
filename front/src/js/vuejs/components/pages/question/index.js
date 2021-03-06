'use strict';

import Vue from 'vue/dist/vue';

let template = require('./template.html');
template     = eval(`\`${template}\``);

import bus from '../../bus/index.js';
import router from '../../../router/index.js';
import onTransitionEnd from '../../../utils/transitionend.js';
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
    question: null,
    isRaised: true,
    selectedAnswer: 0, /* 1 - 5, 0 si inconnu */
    answersStyles: [],
    isTransitionEnded: [false, false, false, false, false],
    headerLinks: {
      'question-abandon': { text: 'Abandonner', callback: function(){
        let _this = this;
        fetch("/TenAsMarreDeTonWallpaper/api/algo/restart", {
            method: 'get',
            credentials: 'include'
          }
        )
        // Handle bad http response
        .then(handleHttpError)
        // Handle Json parse
        .then(function(response){ return response.json(); })
        // Handle request errors
        .then(handleRequestError)
        // Reset ok
        .then(function(response){
          router.push({name: 'home'}); return;
        })
        // Error caught
        .catch(function(error){ alert(error.message); console.log(error.message);});
      } }
    },
    randomInt: 0
  };},

  computed:{
    isFirstQuestion(){ return this.question != null && this.question.number == 1; },
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
      
      Promise.all([ // on promise aussi avec les transitions visuelles, afin que le tout soit fluide
        _this.riseUpAnswers(id),
        fetch("/TenAsMarreDeTonWallpaper/api/algo/getNextQuestion/"+id, {
            method: 'get',
            credentials: 'include'
          }
        )
        // Handle bad http response
        .then(handleHttpError)
        // Handle Json parse
        .then(function(response){ return response.json(); })
        // Handle request errors
        .then(handleRequestError)
        // Next Question ok
        .then(function(response){
          if('continue' in response && response.continue == false){
            if(!('data' in response)) throw Error('Données de résultats manquantes.');
            bus.results = response.data.wallpapers;
            router.push({name: 'results'}); return;
          }
          if(!('data' in response)) throw Error('Données de question manquantes.');
          _this.setQuestion(response.data);
        })
        .then(function(){return;})
      ])
      .then(function(){ _this.riseDownAnswers(id); })
      // Error caught
      .catch(function(error){ alert(error.message); console.log(error.message); _this.riseDownAnswers(id)});

    },
    riseUpAnswers(id){
        for(let i = 0 ; i<5 ; ++i){
            this.answersStyles[i] = {transitionDelay: (0.1 * Math.abs(i - id))+'s'};
        }

        this.isRaised = true;

        return Promise.all([
          onTransitionEnd(this.$refs.answer1.$el, 500),
          onTransitionEnd(this.$refs.answer2.$el, 500),
          onTransitionEnd(this.$refs.answer3.$el, 500),
          onTransitionEnd(this.$refs.answer4.$el, 500),
          onTransitionEnd(this.$refs.answer5.$el, 500),
        ]);
    },
    riseDownAnswers(id){
        /*for(let i = 0 ; i<5 ; ++i){
            this.answersStyles[i] = {transitionDelay: (0.1 * Math.abs(i - id))+'s'};
        }*/ // these are already computed

        this.isRaised = false;
    },
    getQuestion(){
        let _this = this;

        fetch("/TenAsMarreDeTonWallpaper/api/algo/currentQuestion", {
              method: 'get',
              credentials: 'include'
            }
          )
          // Handle bad http response
          .then(handleHttpError)
          // Handle Json parse
          .then(function(response){ return response.json(); })
          // Handle request errors
          .then(handleRequestError)
          // Current Question ok
          .then(function(response){
            if(!('data' in response)) throw Error('Données de question manquantes.');
            _this.setQuestion(response.data);
          })
          .then(function(){ _this.riseDownAnswers(2); })
          // Error caught
          .catch(function(error){ alert(error.message); console.log(error.message);});
    },
    prevQuestion(){
        let _this = this;
        if(_this.isRaised) return false;

        Promise.all([ // on promise aussi avec les transitions visuelles, afin que le tout soit fluide
          _this.riseUpAnswers(0),
          fetch("/TenAsMarreDeTonWallpaper/api/algo/undo", {
              method: 'get',
              credentials: 'include'
            }
          )
          // Handle bad http response
          .then(handleHttpError)
          // Handle Json parse
          .then(function(response){ return response.json(); })
          // Handle request errors
          .then(handleRequestError)
          // Next Question ok
          .then(function(response){
            if(!('data' in response)) throw Error('Données de question manquantes.');
            _this.setQuestion(response.data);
          })
          .then(function(){return;})
        ])
        .then(function(){ _this.riseDownAnswers(0); })
        // Error caught
        .catch(function(error){ alert(error.message); console.log(error.message); _this.riseDownAnswers(0)});
    },
    setQuestion(data){
      this.question = {
        number: data.numero,
        text: data.q_longue,
        quote: null,
        quoteAuthor: null,
        answerCategories: data.reponses
      }
      this.randomInt = Math.random();
    }
  },

  mounted(){
    this.getQuestion();
  },
  
});

export default QuestionPage;
