import React from 'react';
import { Button } from 'semantic-ui-react';
import { Switch, Route, Link } from 'react-router-dom';

import Profil from '../../containers/Profil';
import ProfileModify from '../../containers/ProfilModify';

import './index.scss';

const ProfilMenu = () => (
  <div className="background">
    <div className="user-block">
      <Button as={Link} to="/profile" className="modify_button">Profile</Button>
      <Button as={Link} to="/profilemodify" className="modify_button">Modifier</Button>
    </div>
    <Switch>
      <Route path="/profile" component={Profil} />
      <Route path="/profilemodify" component={ProfileModify} />
    </Switch>
  </div>
);

export default ProfilMenu;
