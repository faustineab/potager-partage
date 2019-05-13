import React from 'react';
import { Menu } from 'semantic-ui-react';
import { NavLink } from 'react-router-dom';

const MenuTest = () => (
  <Menu>
    <Menu.Item name="subscribe" as={NavLink} to="/subscribe">
      Inscription
    </Menu.Item>
    <Menu.Item name="create" as={NavLink} to="/create-garden">
      Créer
    </Menu.Item>
  </Menu>
);

export default MenuTest;
