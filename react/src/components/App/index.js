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
import Login from '../../containers/Login';
import Profil from '../../containers/Profil';
import ProfilModify from '../../containers/ProfilModify';
import ProfilMenu from '../ProfilMenu';

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
      <Route path="/profil" component={Profil} />
      <Route path="/profilmodify" component={ProfilModify} />
      <Route path="/profilmenu" component={ProfilMenu} />
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
