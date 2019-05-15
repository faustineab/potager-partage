/**
 * NPM import
 */
import '@babel/polyfill';
import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';
import { BrowserRouter as Router } from 'react-router-dom';
import { PersistGate } from 'redux-persist/integration/react';


/**
 * Local import
 */
import { store, persistor } from 'src/store';
import App from 'src/containers/App';
// store

/**
 * Code
 */
const rootComponent = (
  <Provider store={store}>
    <PersistGate loading={null} persistor={persistor}>
      <Router>
        <App />
      </Router>
    </PersistGate>
  </Provider>

);

render(rootComponent, document.getElementById('root'));
