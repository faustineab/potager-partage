import { connect } from 'react-redux';
import ProfileModify from '../components/ProfilModify';
import { inputChange, ModifyUserInfos } from '../store/reducer';

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
    dispatch(inputChange(name, value));
  },
  ModifyUserInfos: () => {
    dispatch(ModifyUserInfos());
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ProfileModify);
