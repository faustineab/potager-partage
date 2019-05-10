import { connect } from 'react-redux';

import JoinGarden from 'src/components/Subscribe/joinGarden';

import { inputChange } from '../store/reducer';

const mapStateToProps = state => ({
  gardenList: state.gardenList,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(JoinGarden);
