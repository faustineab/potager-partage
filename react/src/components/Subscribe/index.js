import React from 'react';
import { Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import './index.scss';

const Subscribe = () => (
  <main className="subscription">
    <div className="subscriptionForm">
      <Button className="button" as={Link} to="/create-garden">Créer un potager</Button>
      <Button className="button" as={Link} to="/join-garden">Rejoindre un potager</Button>
    </div>
  </main>
);

export default Subscribe;
