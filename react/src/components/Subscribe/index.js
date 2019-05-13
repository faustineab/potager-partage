import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Form } from 'semantic-ui-react';
import { withRouter } from 'react-router-dom';

import './index.scss';

class Subscribe extends Component {
  redirect = '';

  handleSubmit = (evt) => {
    evt.preventDefault();
    const { history, onFormSubmit } = this.props;
    history.push(this.redirect);
    onFormSubmit();
  }

  handleClick =(evt) => {
    this.redirect = evt.target.name;
  }

  handleChange =(evt) => {
    const { inputChange } = this.props;
    const { name } = evt.target;
    const { value } = evt.target;
    console.log(name, value);
    inputChange(name, value);
  }

  render() {
    const {
      firstName,
      lastName,
      email,
      phoneNumber,
      address,
      password,
    } = this.props;
    return (
      <main className="subscription">
        <Form className="subscriptionForm" onSubmit={this.handleSubmit}>
          <h1>Inscription</h1>
          <Form.Field>
            <label>Prénom</label>
            <input placeholder="Prénom" value={firstName} name="firstName" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>Nom</label>
            <input placeholder="Nom" value={lastName} name="lastName" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>e-mail</label>
            <input type="text" placeholder="e-mail" value={email} name="email" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>téléphone</label>
            <input type="text" placeholder="téléphone" value={phoneNumber} name="phoneNumber" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>adresse</label>
            <input type="text" placeholder="ex : 12 rue des jardiniers ..." value={address} name="address" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>mot de passe</label>
            <input type="password" placeholder="mot de passe" value={password} name="password" onChange={this.handleChange} />
          </Form.Field>
          <Form.Group id="buttons">
            <button type="submit" name="create-garden" className="subscriptionButton" onClick={this.handleClick}>Créer un potager</button>
            <button type="submit" name="join-garden" className="subscriptionButton" onClick={this.handleClick}>Rejoindre un potager</button>
          </Form.Group>
        </Form>
      </main>
    );
  }
}

Subscribe.propTypes = {
  firstName: PropTypes.string,
  lastName: PropTypes.string,
  email: PropTypes.string,
  password: PropTypes.string,
  phoneNumber: PropTypes.string,
  address: PropTypes.string,
  inputChange: PropTypes.func.isRequired,
  onFormSubmit: PropTypes.func.isRequired,
};

Subscribe.defaultProps = {
  firstName: '',
  lastName: '',
  email: PropTypes.string,
  password: PropTypes.string,
  phoneNumber: PropTypes.string,
  address: PropTypes.string,
};

export default withRouter(Subscribe);
