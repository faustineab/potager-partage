import { connect } from 'react-redux';

import CreateGarden from 'src/components/Subscribe/createGarden';

import { inputChange, createGarden } from '../store/reducer';


const mapStateToProps = state => ({
  gardenName: state.gardenName,
  gardenAddress: state.gardenAddress,
  gardenAddressSpecificities: state.gardenAddressSpecificities,
  gardenZipcode: state.gardenZipcode,
  gardenCity: state.gardenCity,
  gardenNbPlotsRow: state.gardenNbPlotsRow,
  gardenNbPlotsColumn: state.gardenNbPlotsColumn,
  gardenSize: state.gardenSize,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  createGarden: () => {
    dispatch(createGarden());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CreateGarden);
