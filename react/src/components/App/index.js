import React from 'react';

import { Switch, Route, Redirect } from 'react-router-dom';
import PropTypes from 'prop-types';


/**
 * Local import
 */
// Composant
import Subscribe from 'src/containers/Subscribe';
import CreateGarden from 'src/containers/CreateGarden';
import JoinGarden from 'src/containers/JoinGarden';
import Login from 'src/containers/Login';
import ChooseGarden from 'src/containers/ChooseGarden';
import Profil from 'src/containers/Profil';
import ProfilModify from 'src/containers/ProfilModify';
import Forum from 'src/containers/Forum';
import PostDetail from 'src/components/PostDetail';
import MainMenu from 'src/containers/MainMenu';
import Footer from 'src/containers/Footer';
import Potager from 'src/containers/Potager';

// Styles et assets


import './app.scss';


const App = ({ loggedIn, loginStatus }) => (
  <div id="app">
    {loggedIn && <MainMenu />}
    <Switch>
      <Route
        exact
        path="/"
        render={() => (
          (loginStatus === 'loggedIn')
            ? <Redirect to="/potager" />
            : (loginStatus === 'chooseGarden')
              ? <Redirect to="/choose-garden" />
              : <Login />
        )}
      />
      <Route path="/choose-garden" component={ChooseGarden} />
      <Route path="/subscribe" component={Subscribe} />
      <Route path="/create-garden" component={CreateGarden} />
      <Route path="/join-garden" component={JoinGarden} />
      <Route
        path="/logout"
        render={() => (<Redirect to="/" />)}
      />
      <Route
        path="/profile"
        render={() => (loggedIn ? (<Profil />) : (<Redirect to="/" />))}
      />
      <Route
        path="/modify-profile"
        render={() => (loggedIn ? (<ProfilModify />) : (<Redirect to="/" />))}
      />
      <Route
        path="/potager"
        render={() => (loggedIn ? (<Potager />) : (<Redirect to="/" />))}
      />
      <Route
        path="/forum"
        exact
        render={() => (loggedIn ? (<Forum />) : (<Redirect to="/" />))}
      />
      <Route
        path="/forum/post/:id"
        render={() => (loggedIn ? (<PostDetail />) : (<Redirect to="/" />))}
      />
    </Switch>
    <Footer />
  </div>
);

App.propTypes = {
  loggedIn: PropTypes.bool.isRequired,
  loginStatus: PropTypes.string.isRequired,
};

export default App;
