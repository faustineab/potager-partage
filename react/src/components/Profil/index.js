import React from 'react';
import PropTypes from 'prop-types';
import { Image } from 'semantic-ui-react';

const Profil = ({ firstName, lastName, address1, email, phoneNumber }) => (
  <div className="background">
    <div className="user-block">
      <Image className="avatar" src="https://react.semantic-ui.com/images/avatar/large/daniel.jpg" size="medium" circular />
      <h1>{firstName} {lastName}</h1>
      <h2>{address1}</h2>
      <h2>{email}</h2>
      <h2>{phoneNumber}</h2>
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
