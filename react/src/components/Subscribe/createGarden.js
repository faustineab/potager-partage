import React from 'react';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';


const CreateGarden = () => (
  <main className="subscription">
    <Form className="subscriptionForm">
      <Form.Field>
        <h1>Créer votre jardin</h1>
        <label>Prénom</label>
        <input placeholder='Prénom' />
      </Form.Field>
      <Form.Field>
        <label>Nom</label>
        <input placeholder='Nom' />
      </Form.Field>
      <Button type='submit'>Submit</Button>
    </Form>
  </main>
);

export default CreateGarden;
