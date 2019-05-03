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

  handleChange =(evt) => {
    console.log(evt.target.name);
  }

  render() {
    return (
      <main className="subscription">
        <Form className="subscriptionForm" onSubmit={this.handleSubmit}>
          <h1>Inscription</h1>
          <Form.Field>
            <label>Prénom</label>
            <input placeholder="Prénom" name="firstName" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>Nom</label>
            <input placeholder="Nom" name="lastName" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>e-mail</label>
            <input type="text" placeholder="e-mail" name="email" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>téléphone</label>
            <input type="text" placeholder="téléphone" name="phoneNumber" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>adresse 1</label>
            <input type="text" placeholder="ex : 12 rue des jardiniers ..." name="address1" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>adresse 2</label>
            <input type="text" placeholder="" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>code postal</label>
            <input type="text" placeholder="code postal" name="zipcode" onChange={this.handleChange} />
          </Form.Field>
          <Form.Field>
            <label>mot de passe</label>
            <input type="password" placeholder="mot de passe" name="password" onChange={this.handleChange} />
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
