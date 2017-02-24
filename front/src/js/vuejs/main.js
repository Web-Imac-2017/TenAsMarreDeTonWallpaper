'use strict';

import Vue       from 'vue/dist/vue';
import VueRouter from 'vue-router';

import App       from './components/app';
import Router    from './router';

Vue.use(VueRouter);

let initApp = function () {
  const app = new App({
    router: Router,
    el    : '#app',
  });
}

export default initApp;
