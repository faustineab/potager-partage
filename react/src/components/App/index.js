import React from 'react';
import {
  Switch, Route,
} from 'react-router-dom';
import PropTypes from 'prop-types';

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
import MainMenu from '../MainMenu';
import Footer from '../../containers/Footer';
import Potager from '../Potager';

import './app.scss';

const App = ({ loggedIn }) => (
  <div id="app">
    {loggedIn && <MainMenu />}
    <Switch>
      <Route exact path="/" component={Login} />
      <Route path="/subscribe" component={Subscribe} />
      <Route path="/create-garden" component={CreateGarden} />
      <Route path="/join-garden" component={JoinGarden} />
      <Route path="/profile" component={Profil} />
      <Route path="/modify-profile" component={ProfilModify} />
      <Route path="/potager" component={Potager} />
    </Switch>
    <Footer />
  </div>
);

App.propTypes = {
  loggedIn: PropTypes.bool.isRequired,
};

export default App;
