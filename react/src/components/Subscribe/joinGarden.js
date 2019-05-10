import React from 'react';
import PropTypes from 'prop-types';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';

const JoinGarden = ({ gardenList }) => {
  console.log('gardenList create', gardenList);

  return (
    <main className="subscription">
      <Form className="subscriptionForm">
        <h1>Rejoindre un jardin</h1>
        <Form.Select options={gardenList} placeholder="Choisissez un jardin" />
        <Button type="submit">Rejoindre</Button>
      </Form>
    </main>
  );
};


JoinGarden.propTypes = {
  gardenList: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.number,
    name: PropTypes.string,
  })),
};

JoinGarden.defaultProps = {
  gardenList: [],
};

export default JoinGarden;
