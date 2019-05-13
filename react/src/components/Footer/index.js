import React from 'react';
import { Link } from 'react-router-dom';

import './index.scss';

/**
 * Code
 */
const Footer = ({ loggedIn }) => (
  <div className="footer">
    {loggedIn
  && (
  <div className="listbox">
    <Link to="/profile"><h1>Réglement</h1></Link>
    <Link to="/profile"><h1>Annuaire</h1></Link>
    <Link to="/profile"><h1>Liens utiles</h1></Link>
  </div>
  )}
    <div className="listbox">
      <Link to="/profile"><h1>Conditions d'utilisation</h1></Link>
      <Link to="/profile"><h1>Contacts</h1></Link>
    </div>
  </div>
);

/**
 * Export
 */
export default Footer;
