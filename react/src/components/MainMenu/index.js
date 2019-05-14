import React from 'react';
import { Menu } from 'semantic-ui-react';
import { NavLink } from 'react-router-dom';

import './index.scss';

const MainMenu = () => (
  <div className="menufix">
    <Menu>
      <div className="menudiv">
        <Menu.Item as={NavLink} to="/potager" className="menuitem" name="Potager">
          <h1>Potager</h1>
        </Menu.Item>
      </div>

      <div className="menudiv">
        <Menu.Item as={NavLink} to="/forum" className="menuitem" name="Forum">
          <h1>Forum</h1>
        </Menu.Item>
      </div>
      <Menu.Menu position="right">
        <div className="menudiv">
          <Menu.Item as={NavLink} to="/profile" className="menuitem" name="Profil">
            <h1>Profil</h1>
          </Menu.Item>
        </div>
        <div className="menudiv">
          <Menu.Item className="menuitem" name="Déconnexion">
            <h1>Déconnexion</h1>
          </Menu.Item>
        </div>
      </Menu.Menu>
    </Menu>
  </div>
);

export default MainMenu;
