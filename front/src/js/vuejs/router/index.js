'use strict';

import VueRouter from 'vue-router';

import Home from '../components/pages/home';
import Question from '../components/pages/question';
import TestDlWpp from '../components/pages/test-dlwpp';

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
    },
    {
      name     : 'test-dlwpp',
      path     : '/test-dlwpp',
      component: TestDlWpp,
    },
  ],
});

export default router;
