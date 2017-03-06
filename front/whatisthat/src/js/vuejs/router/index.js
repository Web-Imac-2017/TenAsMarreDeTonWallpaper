'use strict';

import VueRouter from 'vue-router';

import Home from '../components/pages/home';

const router = new VueRouter({
  mode  : 'history',
  base  : '/TenAsMarreDeTonWallpaper/',
  routes: [
    {
      name     : 'home',
      path     : '/',
      component: Home,
    },
  ],
});

export default router;
