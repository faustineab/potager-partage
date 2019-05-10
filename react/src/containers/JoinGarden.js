import { connect } from 'react-redux';

import JoinGarden from 'src/components/Subscribe/joinGarden';

import { inputChange, joinGarden } from '../store/reducer';

const mapStateToProps = state => ({
  gardenList: state.gardenList,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  onSubmit: () => {
    dispatch(joinGarden());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(JoinGarden);
