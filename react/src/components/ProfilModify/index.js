import React from 'react';
import { Form, Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';

import './index.scss';

const ProfileModify = ({
  firstName, lastName, email, phoneNumber, gardenAddress, gardenAddressSpecificities, gardenZipcode, inputChange, ModifyUserInfos,
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
          <label>téléphone</label>
          <input placeholder="téléphone" value={phoneNumber} name="phoneNumber" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>adresse 1</label>
          <input placeholder="numéro et nom de la voie" value={gardenAddress} name="gardenAddress" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>adresse 2</label>
          <input placeholder="informations complémentaires" value={gardenAddressSpecificities} name="gardenAddressSpecificities" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>code postal</label>
          <input placeholder="code postal" value={gardenZipcode} name="gardenZipcode" onChange={handleInputChange} />
        </Form.Field>
        <Form.Group id="buttons">
          <Button className="submit_button" as={Link} to="/profile">Annuler</Button>
          <Button className="submit_button" type="submit">Confirmer</Button>
        </Form.Group>
      </Form>
    </main>
  );
};

ProfileModify.propTypes = { 
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  email: PropTypes.string,
  phoneNumber: PropTypes.string,
  inputChange: PropTypes.func.isRequired,
  ModifyUserInfos: PropTypes.func.isRequired,
  gardenAddress: PropTypes.string.isRequired,
};

ProfileModify.defaultProps = {
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  email: PropTypes.string,
  phoneNumber: PropTypes.string,
};

export default ProfileModify;
