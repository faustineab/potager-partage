import React from 'react';
import PropTypes from 'prop-types';
import { Form, Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import './index.scss';

const Login = ({ username, password, inputChange, loginMessage, onFormSubmit, loading }) => {

  const handleInputChange = (evt) => {
    const { name } = evt.target;
    const { value } = evt.target;
    console.log(name, value);
    inputChange(name, value);
  };

  const handleSubmit = (evt) => {
    evt.preventDefault();
    onFormSubmit();
  };

  return (
    <main className="subscription"> 
      <Form className="subscriptionForm" onSubmit={handleSubmit}>
        <Form.Field>
        <label>Adresse e-mail</label>
          <Form.Input
            loading={loading}
            disabled={loading}
            value={username}
            name="username"
            onChange={handleInputChange}
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
            name="password"
            onChange={handleInputChange}
            placeholder="Mot de passe"
          />
        </Form.Field>
        <Button loading={loading} disabled={loading} type="submit">Connexion</Button>
        <Link to="/subscribe">Vous n'êtes pas encore inscrit ?</Link>
        <label>{loginMessage}</label>
      </Form>
    </main>
  );
};

Login.propTypes = {
  username: PropTypes.string,
  password: PropTypes.string,
  inputChange: PropTypes.func.isRequired,
  loginMessage: PropTypes.string.isRequired,
  onFormSubmit: PropTypes.func.isRequired,
  loading: PropTypes.bool.isRequired,
};

Login.defaultProps = {
  username: '',
  password: '',
};

export default Login;
