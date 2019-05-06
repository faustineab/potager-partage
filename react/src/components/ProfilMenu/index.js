import React from 'react';
import { Button } from 'semantic-ui-react';
import { Switch, Route, Link } from 'react-router-dom';

import Profil from '../../containers/Profil';
import ProfileModify from '../../containers/ProfilModify';

import './index.scss';

const ProfilMenu = () => (
  <div className="background">
    <div className="user-block">
      <Link to="profile"><Button className="modify_button" inverted color="olive">Profile</Button></Link>
      <Link to="profilemodify"><Button className="modify_button" inverted color="olive">Modifier</Button></Link>
    </div>
    <Switch>
      <Route path="/profile" component={Profil} />
      <Route path="/profilemodify" component={ProfileModify} />
    </Switch>
  </div>
);

export default ProfilMenu;
