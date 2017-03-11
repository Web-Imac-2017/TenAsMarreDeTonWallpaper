'use strict';

import VueRouter from 'vue-router';

import Home from '../components/pages/home';
import Question from '../components/pages/question';

const router = new VueRouter({
  mode  : 'history',
  base  : '/TenAsMarreDeTonWallpaper/',
  routes: [
    {
      name     : 'home',
      path     : '/',
      component: Home,
    },
    {
      name    : 'findYourWallpaper',
      path    : '/find',
      component: Question
    }
  ],
});

export default router;
