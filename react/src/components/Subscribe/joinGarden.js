import React from 'react';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';

const JoinGarden = () => (
  <main className="subscription">
    <Form className="subscriptionForm">
      <h1>Rejoindre un jardin</h1>
      <Form.Field>
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

export default JoinGarden;
