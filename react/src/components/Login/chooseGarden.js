import React from 'react';
import PropTypes from 'prop-types';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';

const ChooseGarden = ({ gardenList, inputChange, chooseGarden }) => {
  const handleInputChange = (evt, { name, value }) => {
    inputChange(name, value);
  };

  const handleSubmit = (evt) => {
    evt.preventDefault();
    chooseGarden();
  };


  return (
    <main className="subscription">
      <Form className="subscriptionForm" onSubmit={handleSubmit}>
        <h1>Choisir un jardin</h1>
        <Form.Select name="gardenId" options={gardenList} placeholder="Choisissez un jardin" onChange={handleInputChange} />
        <Button type="submit">Rejoindre</Button>
      </Form>
    </main>
  );
};


ChooseGarden.propTypes = {
  gardenList: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.number,
    name: PropTypes.string,
  })),
  inputChange: PropTypes.func.isRequired,
  chooseGarden: PropTypes.func.isRequired,
};

ChooseGarden.defaultProps = {
  gardenList: [],
};

export default ChooseGarden;
