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
import Login from '../../containers/Login';

// Styles et assets

import './app.scss';

/**
 * Code
 */
const App = () => (
<Router>
      <Switch>
        <Route exact path="/" component={Login} />
        <Route path="/subscribe" component={Subscribe} />
        <Route path="/create-garden" component={CreateGarden} />
        <Route path="/join-garden" component={JoinGarden} />
      </Switch>
    </Router>
);

/**
 * Export
 */
export default App;

/*

<Router>
      <Switch>
        <Route exact path="/" component={Login} />
        <Route path="/subscribe" component={Subscribe} />
        <Route path="/create-garden" component={CreateGarden} />
        <Route path="/join-garden" component={JoinGarden} />
      </Switch>
    </Router>

    */
