import React from 'react';
import PropTypes from 'prop-types';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';

const JoinGarden = ({ gardenList, inputChange, joinGarden }) => {
  const handleInputChange = (evt, { name, value }) => {
    inputChange(name, value);
  };

  const handleSubmit = (evt) => {
    evt.preventDefault();
    joinGarden();
  };


  return (
    <main className="subscription">
      <Form className="subscriptionForm" onSubmit={handleSubmit}>
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
  joinGarden: PropTypes.func.isRequired,
};

JoinGarden.defaultProps = {
  gardenList: [],
};

export default JoinGarden;
