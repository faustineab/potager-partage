import React from 'react';
import PropTypes from 'prop-types';
import { Form, Button } from 'semantic-ui-react';

import './index.scss';

const Login = ({ username, password, usernameChange, passwordChange, loginMessage, onFormSubmit, loading }) => {

  const handleChangeUsername = (evt) => {
    const { value } = evt.target;
    usernameChange(value);
  }

  const handleChangePassword = (evt) => {
    const { value } = evt.target;
    passwordChange(value);
  }

  const handleSubmit = (evt) => {
    evt.preventDefault();
    onFormSubmit();
  }
  
  return (
    <main className="subscription">
      
      <Form className="subscriptionForm" onSubmit={handleSubmit}> 
        <Form.Field>
        <label>Adresse e-mail</label>
          <Form.Input
            loading={loading}
            disabled={loading}
            value={username}
            onChange={handleChangeUsername}
            placeholder="Adresse e-mail"
          />
        </Form.Field>
        <Form.Field>
        <label>Mot de passe</label>
          <Form.Input
            type="password"
            loading={loading}
            disabled={loading}
            value={password}
            onChange={handleChangePassword}
            placeholder="Mot de passe"
          />
        </Form.Field>
        <Button loading={loading} disabled={loading} type='submit'>Connexion</Button> 
        <label>{loginMessage}</label>
      </Form>
    </main>
  );

};

Login.propTypes = {
  username: PropTypes.string.isRequired,
  password: PropTypes.string.isRequired,
  usernameChange: PropTypes.func.isRequired,
  passwordChange: PropTypes.func.isRequired,
  loginMessage: PropTypes.string.isRequired,
  onFormSubmit: PropTypes.func.isRequired,
  loading: PropTypes.bool.isRequired,
};

export default Login;

/*


          <Form.Input
            loading={loading}
            disabled={loading}
            value={username}
            onChange={handleChangeUsername}
            placeholder="Adresse e-mail"
          />

          */
