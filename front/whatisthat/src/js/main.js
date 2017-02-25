'use strict';

import domready  from 'domready';

import InitApp from './vuejs/main.js';

domready(() => {
  console.log('domready');
  InitApp();
});
