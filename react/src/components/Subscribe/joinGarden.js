import React from 'react';
import PropTypes from 'prop-types';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';

const JoinGarden = ({ gardenList, inputChange }) => {
  console.log('gardenList create', gardenList);

  const handleInputChange = (evt, { name, value }) => {
    console.log(name, value);

    inputChange(name, value);
  };


  return (
    <main className="subscription">
      <Form className="subscriptionForm">
        <h1>Rejoindre un jardin</h1>
        <Form.Select name="gardenId" options={gardenList} placeholder="Choisissez un jardin" onChange={handleInputChange} />
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
  inputChange: PropTypes.func.isRequired,
};

JoinGarden.defaultProps = {
  gardenList: [],
};

export default JoinGarden;
