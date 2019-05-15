import { connect } from 'react-redux';

import ChooseGarden from 'src/components/Login/chooseGarden';

import { inputChange, fetchGardenInfos } from '../store/reducer';

const mapStateToProps = state => ({
  gardenList: state.gardenList,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  chooseGarden: () => {
    dispatch(fetchGardenInfos());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ChooseGarden);
