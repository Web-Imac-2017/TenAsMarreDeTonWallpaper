'use strict';

import VueRouter from 'vue-router';

import Page1 from '../components/page1'
import Page2 from '../components/page2'
import Page3 from '../components/page3'
import Home from '../components/pages/home'

const router = new VueRouter({
  mode  : 'history',
  base  : '/',
  routes: [
    {
      name     : 'page1',
      path     : '/page1',
      component: Page1,
    },
    {
      name     : 'page2',
      path     : '/page2',
      component: Page2,
    },
    {
      name     : 'page3',
      path     : '/page3/:item_id',
      component: Page3,
    },
    {
      name     : 'home',
      path     : '/',
      component: Home,
    },
  ],
});

export default router;
