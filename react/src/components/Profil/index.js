import React from 'react';
import PropTypes from 'prop-types';
import { Image } from 'semantic-ui-react';
import { Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import './index.scss';

const Profil = ({ firstName, lastName, address1, email, phoneNumber }) => (
  <div className="background">
    <div className="user-block">
      <Image className="avatar" src="https://www.natura-dis.com/wp-content/uploads/sites/10/2014/07/devenir-jardinier-botanique-1.jpg" size="medium" circular />
      <h1>{firstName} {lastName}</h1>
      <h2>{address1}</h2>
      <h2>{email}</h2>
      <h2>{phoneNumber}</h2>
      <Button as={Link} to="/modify-profile" className="modify_button">Modifier</Button>
    </div>
  </div>
);

Profil.propTypes = {
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  phoneNumber: PropTypes.string,
  address1: PropTypes.string,
};

Profil.defaultProps = {
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  phoneNumber: PropTypes.string,
  address1: PropTypes.string,
};

export default Profil;
