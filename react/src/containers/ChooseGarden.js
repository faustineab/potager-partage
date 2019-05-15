import { connect } from 'react-redux';

import ChooseGarden from 'src/components/Login/chooseGarden';

import { inputChange, chooseGarden } from '../store/reducer';

const mapStateToProps = state => ({
  gardenList: state.gardenList,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  joinGarden: () => {
    dispatch(chooseGarden());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ChooseGarden);
