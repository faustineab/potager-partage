/**
 * Import
 */
import React from 'react';
import { BrowserRouter as Router, Switch, Route, Redirect } from 'react-router-dom';

/**
 * Local import
 */
// Composants
import Subscribe from 'src/components/Subscribe';
import CreateGarden from 'src/components/Subscribe/createGarden';
import JoinGarden from 'src/components/Subscribe/joinGarden';

// Styles et assets

import './app.scss';

/**
 * Code
 */
const App = () => (
  <div id="app">
    <Router>
      <Switch>
        <Route path="/subscribe" component={Subscribe} />
        <Route path="/create-garden" component={CreateGarden} />
        <Route path="/join-garden" component={JoinGarden} />
      </Switch>
    </Router>
  </div>
);

/**
 * Export
 */
export default App;