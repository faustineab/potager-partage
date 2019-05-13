/**
 * NPM import
 */
import '@babel/polyfill';
import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';
import {
  BrowserRouter as Router,
} from 'react-router-dom';

/**
 * Local import
 */
import store from 'src/store';
import App from 'src/containers/App';
// store

/**
 * Code
 */
const rootComponent = (
  <Provider store={store}>
    <Router>
      <App />
    </Router>
  </Provider>

);

render(rootComponent, document.getElementById('root'));
