import React from 'react';
import { Button } from 'semantic-ui-react';


import './index.scss';

const Subscribe = () => (
  <main id="subscription">
    <div id="subscriptionChoice">
      <Button className="button">Créer un potager</Button>
      <Button>Rejoindre un potager</Button>
    </div>
  </main>
);

export default Subscribe;
