/**
 * Import
 */
import React from 'react';
import { BrowserRouter as Router, Switch, Route, Redirect } from 'react-router-dom';

/**
 * Local import
 */
// Composants

import Subscribe from 'src/containers/Subscribe';
import CreateGarden from 'src/containers/CreateGarden';
import JoinGarden from 'src/containers/JoinGarden';
import Login from 'src/containers/Login';

// Styles et assets

import './app.scss';

/**
 * Code
 */
const App = () => (
  <div id="app">
    <Router>
      <Switch>
        <Route exact path="/" component={Login} />
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
