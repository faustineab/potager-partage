import React from 'react';
import { Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';


import './index.scss';

const ProfilMenu = () => (
  <div className="background">
    <div className="user-block">
      <Button as={Link} to="/profile" className="modify_button">Profile</Button>
      <Button as={Link} to="/modify-profile" className="modify_button">Modifier</Button>
    </div>
  </div>
);

export default ProfilMenu;
