'use strict';

import VueRouter from 'vue-router';

import Home from '../components/pages/home';
import Question from '../components/pages/question';
import Results from '../components/pages/results';
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
<<<<<<< HEAD
      name    : 'findYourWallpaper',
      path    : '/find',
      component: Question
    },
    {
      name    : 'results',
      path    : '/results',
      component: Results
    }
=======
      name     : 'test-dlwpp',
      path     : '/test-dlwpp',
      component: TestDlWpp,
    },
>>>>>>> dlwpp
  ],
});

export default router;
