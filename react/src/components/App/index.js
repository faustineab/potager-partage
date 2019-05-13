/**
 * Import
 */
import React from 'react';
import {
  BrowserRouter as Router, Switch, Route,
} from 'react-router-dom';

/**
 * Local import
 */
// Composants

import Subscribe from 'src/containers/Subscribe';
import CreateGarden from 'src/containers/CreateGarden';
import JoinGarden from 'src/containers/JoinGarden';
import Login from 'src/containers/Login';
import Profil from 'src/containers/Profil';
import ProfilModify from 'src/containers/ProfilModify';
import Forum from 'src/components/Forum';
import ProfilMenu from 'src/components/ProfilMenu';

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
      <Route path="/profile" component={Profil} />
      <Route path="/modify-profile" component={ProfilModify} />
      <Route path="/profile-menu" component={ProfilMenu} />
      <Route path="/forum" component={Forum} />
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
