'use strict';

import VueRouter from 'vue-router';

import Home from '../components/pages/home';
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
      name     : 'test-dlwpp',
      path     : '/test-dlwpp',
      component: TestDlWpp,
    },
  ],
});

export default router;
