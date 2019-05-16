import React from 'react';
import PropTypes from 'prop-types';
import { Image } from 'semantic-ui-react';
import { Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import './index.scss';

const Profil = ({ firstName, lastName, gardenAddress, email, phoneNumber }) => (
  <div className="background">
    <div className="user-block">
      <Image className="avatar" src="https://www.shareicon.net/data/512x512/2016/09/01/822718_user_512x512.png" size="medium" circular />
      <h1>{firstName} {lastName}</h1>
      <h2>{gardenAddress}</h2>
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
