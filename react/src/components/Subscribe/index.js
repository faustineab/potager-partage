import React, { Component } from 'react';
import { Form } from 'semantic-ui-react';
import { withRouter } from 'react-router-dom';

import './index.scss';

class Subscribe extends Component {
  redirect='';

  handleSubmit = (evt) => {
    evt.preventDefault();
    this.props.history.push(this.redirect);
  }

  handleClick =(evt) => {
    this.redirect = evt.target.name;
  }

  render() {
    return (
      <main className="subscription">
        <Form className="subscriptionForm" onSubmit={this.handleSubmit}>
          <Form.Field>
            <label>Prénom</label>
            <input placeholder="Prénom" name="firstName" />
          </Form.Field>
          <Form.Field>
            <label>Nom</label>
            <input placeholder="Nom" name="lastName" />
          </Form.Field>
          <Form.Field>
            <label>e-mail</label>
            <input placeholder="e-mail" name="email" />
          </Form.Field>
          <Form.Field>
            <label>téléphone</label>
            <input placeholder="téléphone" name="phoneNumber" />
          </Form.Field>
          <Form.Field>
            <label>adresse 1</label>
            <input placeholder="ex : 12 rue des jardiniers ..." name="address1" />
          </Form.Field>
          <Form.Field>
            <label>adresse 2</label>
            <input placeholder="" />
          </Form.Field>
          <Form.Field>
            <label>code postal</label>
            <input placeholder="code postal" name="zipcode" />
          </Form.Field>
          <Form.Field>
            <label>mot de passe</label>
            <input placeholder="mot de passe" name="password" />
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

export default withRouter(Subscribe);
