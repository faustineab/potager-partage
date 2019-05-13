import React from 'react';
import { Form, Button } from 'semantic-ui-react';
import PropTypes from 'prop-types';

import './index.scss';

const ProfileModify = ({
  firstName, lastName, password, email, phoneNumber, address1, address2, zipcode, inputChange, ModifyUserInfos,
}) => {
  const handleInputChange = (evt) => {
    const { name } = evt.target;
    const { value } = evt.target;
    inputChange(name, value);
  };

  const handleSubmit = (evt) => {
    evt.preventDefault();
    ModifyUserInfos();
  };

  return (
    <main className="user-block">
      <Form onSubmit={handleSubmit}>
        <Form.Field>
          <label>Prénom</label>
          <input placeholder="Prénom" value={firstName} name="firstName" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Nom</label>
          <input placeholder="Nom" value={lastName} name="lastName" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>e-mail</label>
          <input placeholder="e-mail" value={email} name="email" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Mot de passe</label>
          <input placeholder="Mot de passe" value={password} name="password" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>téléphone</label>
          <input placeholder="téléphone" value={phoneNumber} name="phoneNumber" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>adresse 1</label>
          <input placeholder="numéro et nom de la voie" value={address1} name="address1" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>adresse 2</label>
          <input placeholder="informations complémentaires" value={address2} name="address2" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>code postal</label>
          <input placeholder="code postal" value={zipcode} name="zipcode" onChange={handleInputChange} />
        </Form.Field>
        <Form.Group id="buttons">
          <Button inverted color="olive" className="submit_button" type="submit">Confirmer</Button>
        </Form.Group>
      </Form>
    </main>
  );
};

ProfileModify.propTypes = {
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  email: PropTypes.string,
  password: PropTypes.string,
  phoneNumber: PropTypes.string,
  address1: PropTypes.string,
  address2: PropTypes.string,
  zipcode: PropTypes.string,
  inputChange: PropTypes.func.isRequired,
  ModifyUserInfos: PropTypes.func.isRequired,
};

ProfileModify.defaultProps = {
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  email: PropTypes.string,
  password: PropTypes.string,
  phoneNumber: PropTypes.string,
  address1: PropTypes.string,
  address2: PropTypes.string,
  zipcode: PropTypes.string,
};

export default ProfileModify;
