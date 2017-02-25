'use strict';

import VueRouter from 'vue-router';

import Page1 from '../components/question'
import Page2 from '../components/background'
import Page3 from '../components/page3'

const router = new VueRouter({
  mode  : 'history',
  base  : '/',
  routes: [
    {
      name     : 'question',
      path     : '/question',
      component: Page1,
    },
    {
      name     : 'background',
      path     : '/background',
      component: Page2,
    },
    {
      name     : 'page3',
      path     : '/page3/:item_id',
      component: Page3,
    },
  ],
});

export default router;
