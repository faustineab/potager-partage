import { connect } from 'react-redux';

import Subscribe from 'src/components/Subscribe';

import { subscriptionInputChange, registerUser } from '../store/reducer';

const mapStateToProps = state => ({
  firstName: state.firstName,
  lastName: state.lastName,
  password: state.password,
  email: state.email,
  phoneNumber: state.phoneNumber,
  address1: state.address1,
  address2: state.address2,
  zipcode: state.zipcode,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(subscriptionInputChange(name, value));
  },
  onFormSubmit: () => {
    dispatch(registerUser());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Subscribe);
