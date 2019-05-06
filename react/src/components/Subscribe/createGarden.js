import React from 'react';
import PropTypes from 'prop-types';
import { Button, Form } from 'semantic-ui-react';

import './index.scss';


const CreateGarden = ({
  gardenName,
  gardenAddress,
  gardenAddressSpecificities,
  gardenZipcode,
  gardenCity,
  gardenNbPlotsRow,
  gardenNbPlotsColumn,
  gardenSize,
  inputChange,

}) => {
  const handleInputChange = (evt) => {
    const { name } = evt.target;
    const { value } = evt.target;
    console.log(name, value);
    inputChange(name, value);
  };

  return (
    <main className="subscription">
      <Form className="subscriptionForm">
        <h1>Créer votre jardin</h1>
        <Form.Field>
          <label>Nom du jardin</label>
          <input placeholder="ex : le jardin des amis" value={gardenName} name="gardenName" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Adresse du jardin</label>
          <input placeholder="ex : 12 rue des jardiniers ..." value={gardenAddress} name="gardenAddress" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Adresse 2</label>
          <input placeholder="ex : lieu-dit la roseraie ..." value={gardenAddressSpecificities} name="gardenAddressSpecificities" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Code Postal</label>
          <input placeholder="code Postal" value={gardenZipcode} name="gardenZipcode" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Ville</label>
          <input placeholder="Ville" value={gardenCity} name="gardenCity" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Taille du jardin (en m²)</label>
          <input placeholder="Taille du jardin" value={gardenSize} name="gardenSize" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Longueur du jardin (en nb de parcelles)</label>
          <input type="number" min="1" max="20" placeholder="Nombre de parcelles en longueur" value={gardenNbPlotsRow} name="gardenNbPlotsRow" onChange={handleInputChange} />
        </Form.Field>
        <Form.Field>
          <label>Largeur du jardin (en nb de parcelles)</label>
          <input type="number" min="1" max="10" placeholder="Nombre de parcelles en largeur" value={gardenNbPlotsColumn} name="gardenNbPlotsColumn" onChange={handleInputChange} />
        </Form.Field>
        <Button type="submit">Créer</Button>
      </Form>
    </main>
  );
};

CreateGarden.propTypes = {
  gardenName: PropTypes.string,
  gardenAddress: PropTypes.string,
  gardenAddressSpecificities: PropTypes.string,
  gardenZipcode: PropTypes.string,
  gardenCity: PropTypes.string,
  gardenNbPlotsRow: PropTypes.number,
  gardenNbPlotsColumn: PropTypes.number,
  gardenSize: PropTypes.string,
};

CreateGarden.defaultProps = {
  gardenName: '',
  gardenAddress: '',
  gardenAddressSpecificities: '',
  gardenZipcode: '',
  gardenCity: '',
  gardenNbPlotsColumn: 1,
  gardenNbPlotsRow: 1,
  gardenSize: '',
};

export default CreateGarden;
