import { connect } from 'react-redux';

import Subscribe from 'src/components/Subscribe';

import { inputChange, fetchGardenlist } from '../store/reducer';

const mapStateToProps = state => ({
  firstName: state.firstName,
  lastName: state.lastName,
  password: state.password,
  email: state.email,
  phoneNumber: state.phoneNumber,
  address: state.address,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  onFormSubmit: () => {
    dispatch(fetchGardenlist());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Subscribe);
